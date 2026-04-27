/**
 * Preload script - Bridge sécurisé entre le processus principal et le rendu
 * Ce fichier s'exécute avant le chargement de la page web
 */

const { contextBridge, ipcRenderer } = require('electron');

// Exposer des API sécurisées au processus de rendu si nécessaire
contextBridge.exposeInMainWorld('electron', {
    platform: process.platform,
    versions: {
        node: process.versions.node,
        chrome: process.versions.chrome,
        electron: process.versions.electron
    }
});

console.log('Preload script chargé');
