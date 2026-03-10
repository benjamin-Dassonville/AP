const { app, BrowserWindow, Menu } = require('electron');
const path = require('path');
const PHPServer = require('./php-server');

let mainWindow;
let phpServer;
let splashWindow;

// Créer le splash screen
function createSplash() {
    splashWindow = new BrowserWindow({
        width: 500,
        height: 400,
        frame: false,
        transparent: true,
        alwaysOnTop: true,
        resizable: false,
        webPreferences: {
            nodeIntegration: false,
            contextIsolation: true
        }
    });

    splashWindow.loadFile('splash.html');
    splashWindow.center();
}

// Fermer le splash screen
function closeSplash() {
    if (splashWindow && !splashWindow.isDestroyed()) {
        splashWindow.close();
        splashWindow = null;
    }
}

// Créer la fenêtre principale
function createWindow(url) {
    mainWindow = new BrowserWindow({
        width: 1400,
        height: 900,
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            nodeIntegration: false,
            contextIsolation: true
        },
        icon: path.join(__dirname, 'assets', 'icon.png'),
        title: 'CashCash - Gestion IT'
    });

    // Charger l'application PHP
    mainWindow.loadURL(url);

    // Gérer les erreurs de chargement (404, etc.)
    mainWindow.webContents.on('did-fail-load', (event, errorCode, errorDescription) => {
        // Ignorer les erreurs mineures
        if (errorCode === -3) return; // ABORTED

        console.error('Erreur de chargement:', errorCode, errorDescription);
        mainWindow.loadFile('error.html');
    });

    // Ouvrir les DevTools en développement
    if (process.env.NODE_ENV === 'development') {
        mainWindow.webContents.openDevTools();
    }

    // Créer le menu de l'application
    createMenu();

    // Quand la fenêtre est fermée, quitter l'application complètement
    mainWindow.on('closed', () => {
        mainWindow = null;
        // Quitter l'application au lieu de juste fermer la fenêtre
        app.quit();
    });
}

// Créer le menu natif
function createMenu() {
    const template = [
        {
            label: 'Fichier',
            submenu: [
                {
                    label: 'Actualiser',
                    accelerator: 'CmdOrCtrl+R',
                    click: () => {
                        if (mainWindow) {
                            mainWindow.reload();
                        }
                    }
                },
                { type: 'separator' },
                {
                    label: 'Quitter',
                    accelerator: 'CmdOrCtrl+Q',
                    click: () => {
                        app.quit();
                    }
                }
            ]
        },
        {
            label: 'Édition',
            submenu: [
                { label: 'Annuler', accelerator: 'CmdOrCtrl+Z', role: 'undo' },
                { label: 'Rétablir', accelerator: 'Shift+CmdOrCtrl+Z', role: 'redo' },
                { type: 'separator' },
                { label: 'Couper', accelerator: 'CmdOrCtrl+X', role: 'cut' },
                { label: 'Copier', accelerator: 'CmdOrCtrl+C', role: 'copy' },
                { label: 'Coller', accelerator: 'CmdOrCtrl+V', role: 'paste' },
                { label: 'Tout sélectionner', accelerator: 'CmdOrCtrl+A', role: 'selectAll' }
            ]
        },
        {
            label: 'Affichage',
            submenu: [
                { label: 'Zoom avant', accelerator: 'CmdOrCtrl+Plus', role: 'zoomIn' },
                { label: 'Zoom arrière', accelerator: 'CmdOrCtrl+-', role: 'zoomOut' },
                { label: 'Zoom par défaut', accelerator: 'CmdOrCtrl+0', role: 'resetZoom' },
                { type: 'separator' },
                { label: 'Plein écran', accelerator: 'F11', role: 'togglefullscreen' }
            ]
        },
        {
            label: 'Aide',
            submenu: [
                {
                    label: 'À propos',
                    click: () => {
                        const { dialog } = require('electron');
                        dialog.showMessageBox(mainWindow, {
                            type: 'info',
                            title: 'À propos',
                            message: 'AP Test - Client Lourd',
                            detail: 'Version 1.0.0\nApplication de gestion des interventions IT'
                        });
                    }
                },
                { type: 'separator' },
                {
                    label: 'Ouvrir les outils de développement',
                    accelerator: 'F12',
                    click: () => {
                        if (mainWindow) {
                            mainWindow.webContents.toggleDevTools();
                        }
                    }
                }
            ]
        }
    ];

    const menu = Menu.buildFromTemplate(template);
    Menu.setApplicationMenu(menu);
}

// Empêcher plusieurs instances de l'application
const gotTheLock = app.requestSingleInstanceLock();

if (!gotTheLock) {
    // Une autre instance tourne déjà
    const { dialog } = require('electron');
    dialog.showErrorBox(
        'Application déjà en cours',
        'AP Test Client Lourd est déjà en cours d\'exécution.\n\n' +
        'Fermez l\'autre instance avant de relancer l\'application.'
    );
    app.quit();
} else {
    // Si quelqu'un essaie de lancer une 2ème instance, focus la première
    app.on('second-instance', () => {
        if (mainWindow) {
            if (mainWindow.isMinimized()) mainWindow.restore();
            mainWindow.focus();
        }
    });

    // Quand Electron est prêt
    app.whenReady().then(async () => {
        try {
            // Afficher le splash screen
            createSplash();

            // Déterminer le chemin correct selon si l'app est packagée ou non
            const path = require('path');
            let appPath;

            if (app.isPackaged) {
                // En production, utiliser le dossier app.asar.unpacked qui contient les fichiers PHP
                appPath = path.join(process.resourcesPath, 'app.asar.unpacked');
                console.log('Mode PRODUCTION - Unpacked path:', appPath);
            } else {
                // En développement, utiliser le dossier courant
                appPath = app.getAppPath();
                console.log('Mode DEVELOPPEMENT - App path:', appPath);
            }

            // Démarrer le serveur PHP
            phpServer = new PHPServer(appPath);
            const serverUrl = await phpServer.start();

            // Attendre un peu pour que le serveur soit vraiment prêt
            await new Promise(resolve => setTimeout(resolve, 500));

            // Fermer le splash et créer la fenêtre principale
            closeSplash();
            createWindow(serverUrl);

            app.on('activate', () => {
                if (BrowserWindow.getAllWindows().length === 0) {
                    createWindow(serverUrl);
                }
            });
        } catch (error) {
            console.error('Erreur lors du démarrage:', error);

            // Fermer le splash en cas d'erreur
            closeSplash();

            // Afficher un message d'erreur à l'utilisateur
            const { dialog } = require('electron');
            dialog.showErrorBox(
                'Erreur de démarrage',
                `Impossible de démarrer le serveur PHP.\n\n` +
                `Erreur: ${error.message}\n\n` +
                `Assurez-vous que PHP est installé.\n` +
                `Chemin XAMPP: /Applications/XAMPP/xamppfiles/bin/php`
            );

            app.quit();
        }
    });
}

// Quitter quand toutes les fenêtres sont fermées (sur toutes les plateformes)
app.on('window-all-closed', () => {
    app.quit();
});

// Arrêter le serveur PHP quand on quitte
app.on('before-quit', (event) => {
    if (phpServer) {
        console.log('Arrêt de l\'application - fermeture du serveur PHP...');
        phpServer.stop();

        // Donner un peu de temps pour que le processus se termine
        // Avant de quitter complètement
        if (phpServer.process && !phpServer.process.killed) {
            event.preventDefault();
            setTimeout(() => {
                phpServer.process = null;
                app.quit();
            }, 500);
        }
    }
});

app.on('will-quit', () => {
    // Dernière chance d'arrêter le serveur PHP
    if (phpServer) {
        phpServer.stop();
    }
});

// Gestion des erreurs
process.on('uncaughtException', (error) => {
    console.error('Erreur non gérée:', error);
});
