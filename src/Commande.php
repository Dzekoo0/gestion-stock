<?php
class Commande {
    private int $numero;
    private array $lignes = [];
    private bool $validee = false;

    public function __construct(int $numero) {
        $this->numero = $numero;
    }

    public function ajouterLigne(Produit $p, int $quantite): void {
        if ($quantite <= 0) {
            throw new InvalidArgumentException("La quantité doit être positive.");
        }
        if ($quantite > $p->getQuantite()) {
            throw new InvalidArgumentException("Quantité supérieure au stock disponible.");
        }
        $this->lignes[] = ['produit' => $p, 'quantite' => $quantite];
    }

    public function total(): float {
        $total = 0.0;
        foreach ($this->lignes as $l) {
            $total += $l['produit']->getPrix() * $l['quantite'];
        }
        return $total;
    }

    public function valider(): void {
        if ($this->validee) {
            throw new LogicException("Commande déjà validée.");
        }
        if (empty($this->lignes)) {
            throw new LogicException("Commande vide.");
        }
        foreach ($this->lignes as $l) {
            $l['produit']->retirerQuantite($l['quantite']);
        }
        $this->validee = true;
    }

    public function estValidee(): bool {
        return $this->validee;
    }

    public function afficher(): string {
        $s = "=== Facture commande n°{$this->numero} ===\n";
        foreach ($this->lignes as $l) {
            $p = $l['produit'];
            $s .= sprintf("%-15s %3d x %8.2f = %9.2f\n",
                $p->getNom(), $l['quantite'], $p->getPrix(),
                $p->getPrix() * $l['quantite']);
        }
        $s .= sprintf("TOTAL : %.2f\n", $this->total());
        $s .= $this->validee ? "Statut : validée\n" : "Statut : en attente\n";
        return $s;
    }
}