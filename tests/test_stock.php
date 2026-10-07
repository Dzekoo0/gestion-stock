<?php

$stock = new Stock();

// 1. Initialisation
verifier($stock->compter() === 0, "Un nouveau stock contient 0 produit");

// 2. Ajout de produits
$p1 = new Produit('P001', 'Clavier', 150.0, 10);
$p2 = new Produit('P002', 'Souris', 50.0, 0);   // En rupture
$p3 = new Produit('P003', 'Tapis', 20.0, 3);    // Sous seuil (seuil = 5)

$stock->ajouter($p1);
$stock->ajouter($p2);
$stock->ajouter($p3);

verifier($stock->compter() === 3, "Le stock contient 3 produits après ajouts");

// 3. Exception sur référence existante
$doublonDetecte = false;
try {
    $stock->ajouter(new Produit('P001', 'Autre Clavier', 100.0, 5));
} catch (InvalidArgumentException $e) {
    $doublonDetecte = true;
}
verifier($doublonDetecte, "ajouter() refuse une référence déjà existante");

verifier($stock->trouver('P001') === $p1, "trouver('P001') renvoie bien l'instance du produit");
verifier($stock->trouver('INCONNU') === null, "trouver() renvoie null pour un produit inexistant");

// 5. Liste de tous les produits
verifier(count($stock->tous()) === 3, "tous() retourne un tableau contenant les 3 produits");

// 6. Valeur totale du stock (10*150 + 0*50 + 3*20 = 1560)
verifier(abs($stock->valeurTotale() - 1560.0) < 0.001, "valeurTotale() calcule la somme exacte des valeurs de stock");

// 7. Produits en rupture et sous seuil
$ruptures = $stock->produitsEnRupture();
verifier(count($ruptures) === 1 && $ruptures[0]->getReference() === 'P002', "produitsEnRupture() identifie le produit dont la quantité est 0");

$sousSeuil = $stock->produitsSousSeuil(5);
verifier(count($sousSeuil) === 2, "produitsSousSeuil(5) filtre les produits avec une quantité strictement inférieure à 5");