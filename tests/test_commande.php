<?php
$prodC = new Produit('C001', 'Souris', 50, 10);
$cmd = new Commande(1);
$cmd->ajouterLigne($prodC, 3);
verifier(abs($cmd->total() - 150) < 0.001, 'Le total vaut 3 x 50');
verifier($cmd->estValidee() === false, 'La commande n\'est pas validée au départ');

$cmd->valider();
verifier($cmd->estValidee() === true, 'La commande est validée');
verifier($prodC->getQuantite() === 7, 'Le stock est passé de 10 à 7');

try {
    $cmd->valider();
    verifier(false, 'Double validation doit lever une exception');
} catch (LogicException $e) {
    verifier(true, 'Double validation refusée');
}

try {
    (new Commande(2))->ajouterLigne($prodC, 100);
    verifier(false, 'Quantité > stock doit lever une exception');
} catch (InvalidArgumentException $e) {
    verifier(true, 'Quantité supérieure au stock refusée');
}