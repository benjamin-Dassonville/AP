const { spawn } = require('child_process');
const path = require('path');
const net = require('net');

class PHPServer {
    constructor(documentRoot = null) {
        this.process = null;
        this.port = null;
        this.documentRoot = documentRoot;
    }

    // Fonction pour trouver un port disponible sans dépendance externe
    // Utilise un point de départ aléatoire pour éviter les conflits
    async findAvailablePort(startPort = 8000, endPort = 9000) {
        // Générer un port de départ aléatoire dans la plage
        const randomOffset = Math.floor(Math.random() * (endPort - startPort));
        const randomStart = startPort + randomOffset;

        console.log(`Recherche de port disponible à partir de ${randomStart}...`);

        // Essayer à partir du port aléatoire jusqu'à la fin
        for (let port = randomStart; port <= endPort; port++) {
            if (await this.isPortAvailable(port)) {
                console.log(`✓ Port ${port} disponible`);
                return port;
            }
        }

        // Si rien trouvé, réessayer depuis le début jusqu'au port aléatoire
        for (let port = startPort; port < randomStart; port++) {
            if (await this.isPortAvailable(port)) {
                console.log(`✓ Port ${port} disponible`);
                return port;
            }
        }

        throw new Error('Aucun port disponible trouvé entre ' + startPort + ' et ' + endPort);
    }

    isPortAvailable(port) {
        return new Promise((resolve) => {
            const server = net.createServer();

            server.once('error', () => {
                resolve(false);
            });

            server.once('listening', () => {
                server.close();
                resolve(true);
            });

            server.listen(port, '127.0.0.1');
        });
    }

    async start() {
        try {
            // Trouver un port disponible entre 8000 et 9000
            const freePort = await this.findAvailablePort(8000, 9000);
            this.port = freePort;

            const phpPath = this.findPHPPath();
            const documentRoot = this.documentRoot || __dirname;

            console.log('======================');
            console.log('Démarrage serveur PHP');
            console.log(`Chemin PHP: ${phpPath}`);
            console.log(`Document root: ${documentRoot}`);
            console.log(`Port: ${this.port}`);
            console.log('======================');

            // Démarrer le serveur PHP built-in
            // Utiliser -t avec guillemets pour gérer les espaces
            this.process = spawn(phpPath, [
                '-S',
                `localhost:${this.port}`,
                '-t',
                documentRoot
            ], {
                cwd: documentRoot  // S'assurer que le CWD est bon
            });

            // Gérer les erreurs de spawn
            this.process.on('error', (err) => {
                console.error('Erreur spawn PHP:', err);
                throw new Error(`Impossible de lancer PHP: ${err.message}`);
            });

            this.process.stdout.on('data', (data) => {
                console.log(`PHP Server: ${data}`);
            });

            this.process.stderr.on('data', (data) => {
                console.error(`PHP Server Error: ${data}`);
            });

            this.process.on('close', (code) => {
                console.log(`Serveur PHP arrêté avec le code ${code}`);
            });

            // Attendre que le serveur démarre complètement
            await new Promise(resolve => setTimeout(resolve, 2000));

            console.log(`✓ Serveur PHP démarré sur http://localhost:${this.port}`);
            return `http://localhost:${this.port}`;
        } catch (error) {
            console.error('Erreur lors du démarrage du serveur PHP:', error);
            throw error;
        }
    }

    findPHPPath() {
        // Chemins possibles pour PHP
        const possiblePaths = [
            '/Applications/XAMPP/xamppfiles/bin/php',  // XAMPP sur macOS
            'C:\\xampp\\php\\php.exe',                 // XAMPP sur Windows
            '/usr/bin/php',                             // Linux/macOS système
            'php'                                       // PATH système
        ];

        // Pour le développement, utiliser PHP XAMPP
        if (process.platform === 'darwin') {
            return '/Applications/XAMPP/xamppfiles/bin/php';
        } else if (process.platform === 'win32') {
            return 'C:\\xampp\\php\\php.exe';
        }

        return 'php';
    }

    stop() {
        if (this.process) {
            console.log('Arrêt du serveur PHP...');
            try {
                // Essayer d'abord un arrêt propre
                this.process.kill('SIGTERM');

                // Si le processus ne s'arrête pas après 2 secondes, forcer
                setTimeout(() => {
                    if (this.process && !this.process.killed) {
                        console.log('Forcer l\'arrêt du serveur PHP...');
                        this.process.kill('SIGKILL');
                    }
                }, 2000);
            } catch (error) {
                console.error('Erreur lors de l\'arrêt du serveur PHP:', error);
            }
            this.process = null;
        }
    }

    getPort() {
        return this.port;
    }

    getURL() {
        return this.port ? `http://localhost:${this.port}` : null;
    }
}

module.exports = PHPServer;
