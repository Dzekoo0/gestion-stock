<?php
$p = new Produit('P001', 'Clavier', 150, 10);
verifier($p->getQuantite() === 10, 'La quantité initiale est 10');
$p->retirerQuantite(3);
verifier($p->getQuantite() === 7, 'Après retrait de 3, il en reste 7');
verifier(abs($p->valeurStock() - 1050) < 0.001, 'La valeur du stock vaut 7 x 150');

$p->ajouterQuantite(5);
verifier($p->getQuantite() === 12, 'Après ajout de 5, il y en a 12');

try {
    $p->retirerQuantite(100);
    verifier(false, 'Retirer plus que le stock lève une exception');
} catch (InvalidArgumentException $e) {
    verifier(true, 'Retirer plus que le stock lève une exception');
}

try {
    new Produit('P002', 'Souris', -5);
    verifier(false, 'Un prix négatif lève une exception');
} catch (InvalidArgumentException $e) {
    verifier(true, 'Un prix négatif lève une exception');
}