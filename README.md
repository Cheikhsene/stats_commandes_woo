# 📊 Statistiques Simples des Commandes WooCommerce 

## 📋 Description
Ce projet est une application web PHP qui affiche des statistiques détaillées des commandes WooCommerce. Il offre une vue d'ensemble claire de l'activité commerciale avec des graphiques interactifs et des informations chiffrées.

## ✨ Fonctionnalités

- 🛒 Affichage du nombre total de commandes
- ✅ Statistiques des commandes terminées (nombre et montant total)
- ❌ Statistiques des commandes annulées (nombre et montant total)
- 💰 Calcul du total combiné des commandes terminées et annulées
- 📊 Graphique circulaire montrant la répartition des statuts de commande
- 📈 Graphique en barres illustrant les montants par statut de commande
- 🌍 Interface responsive adaptée aux appareils mobiles et desktop

## 🛠️ Prérequis

- PHP 7.4 ou supérieur
- Serveur web (Apache, Nginx, etc.)
- Compte WooCommerce avec clés API
- Extension cURL PHP activée

## 🚀 Installation

1. **Cloner le dépôt**
   ```
   git clone https://github.com/CheikhSene/stats_commandes_woo.git
   ```

2. **Configurer les clés API**
   Ouvrez le fichier `statistiques.php` et remplacez les valeurs suivantes :
   ```php
   define('WOOCOMMERCE_STORE_URL', 'https://votre-site-woocommerce.com/');
   define('WOOCOMMERCE_CONSUMER_KEY', 'votre_cle_consommateur');
   define('WOOCOMMERCE_CONSUMER_SECRET', 'votre_cle_secrete');
   ```

3. **Déployer sur votre serveur web**
   Uploadez tous les fichiers sur votre serveur web.

4. **Accéder à l'application**
   Ouvrez votre navigateur et accédez à l'URL où vous avez déployé l'application.

## 🖼️ Capture d'écran

![Interface des statistiques WooCommerce](https://drive.google.com/file/d/13Ko5Sw6bfzFpckbNH0aV_2ZGDhMNkCm3/view?usp=sharing)

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou à soumettre une pull request.

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

Créé avec ❤️ par [CheikhSene](https://cheikhsene.github.io)
