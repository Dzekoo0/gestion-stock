<?php
require __DIR__ . '/autoload.php';

$stock = new Stock();
$numeroCommande = 1;

function lire(string $invite): string {
    echo $invite;
    return trim(fgets(STDIN));
}

do {
    echo "\n=== GESTION DE STOCK ===\n";
    echo "1. Ajouter un produit\n2. Lister le stock\n3. Réapprovisionner\n";
    echo "4. Nouvelle commande\n5. Produits en rupture ou sous seuil\n0. Quitter\n";
    $choix = lire("Votre choix : ");

    try {
        switch ($choix) {
            case '1':
                $ref = lire("Référence : ");
                $nom = lire("Nom : ");
                $prix = (float) lire("Prix : ");
                $qte = (int) lire("Quantité : ");
                $stock->ajouter(new Produit($ref, $nom, $prix, $qte));
                echo "Produit ajouté.\n";
                break;
            case '2':
                foreach ($stock->tous() as $p) {
                    printf("%s | %s | %.2f | qté %d\n", $p->getReference(),
                        $p->getNom(), $p->getPrix(), $p->getQuantite());
                }
                printf("Valeur totale : %.2f\n", $stock->valeurTotale());
                break;
            case '3':
                $p = $stock->trouver(lire("Référence : "));
                if ($p === null) { echo "Produit introuvable.\n"; break; }
                $p->ajouterQuantite((int) lire("Quantité à ajouter : "));
                echo "Stock mis à jour.\n";
                break;
            case '4':
                $cmd = new Commande($numeroCommande++);
                while (($ref = lire("Référence (vide pour terminer) : ")) !== '') {
                    $p = $stock->trouver($ref);
                    if ($p === null) { echo "Produit introuvable.\n"; continue; }
                    try {
                        $cmd->ajouterLigne($p, (int) lire("Quantité : "));
                    } catch (InvalidArgumentException $e) {
                        echo "Erreur : " . $e->getMessage() . "\n";
                    }
                }
                $cmd->valider();
                echo $cmd->afficher();
                break;
            case '5':
                echo "-- En rupture --\n";
                foreach ($stock->produitsEnRupture() as $p) echo $p->getNom() . "\n";
                $seuil = (int) lire("Seuil : ");
                echo "-- Sous le seuil --\n";
                foreach ($stock->produitsSousSeuil($seuil) as $p) {
                    echo $p->getNom() . " (" . $p->getQuantite() . ")\n";
                }
                break;
            case '0':
                echo "Au revoir.\n";
                break;
            default:
                echo "Choix invalide.\n";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
    }
} while ($choix !== '0');