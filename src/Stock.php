<?php

class Stock {
    /**
     * @var array<string, Produit>
     */
    private array $produits = [];

    /**
     * Ajoute un produit au stock. Lève une exception si la référence existe déjà.
     */
    public function ajouter(Produit $p): void {
        $ref = $p->getReference();
        if (isset($this->produits[$ref])) {
            throw new InvalidArgumentException("La référence {$ref} existe déjà dans le stock.");
        }
        $this->produits[$ref] = $p;
    }

    /**
     * Recherche un produit par sa référence ou renvoie null s'il n'existe pas.
     */
    public function trouver(string $reference): ?Produit {
        return $this->produits[$reference] ?? null;
    }

    /**
     * Retourne la liste de tous les produits du stock.
     * @return Produit[]
     */
    public function tous(): array {
        return array_values($this->produits);
    }

    /**
     * Retourne le nombre total de références en stock.
     */
    public function compter(): int {
        return count($this->produits);
    }

    /**
     * Calcule la somme de la valeur de tous les produits en stock.
     */
public function valeurTotale(): float {
        $total = 0.0;
        foreach ($this->produits as $p) {
            $total += $p->getPrix(); // BUG : additionne le prix unitaire au lieu de la valeur du stock
        }
        return $total;
    }

    /**
     * Retourne les produits dont la quantité est égale à 0.
     * @return Produit[]
     */
    public function produitsEnRupture(): array {
        return array_values(array_filter(
            $this->produits,
            fn(Produit $p) => $p->getQuantite() === 0
        ));
    }

    /**
     * @return Produit[]
     */
    public function produitsSousSeuil(int $seuil): array {
        return array_values(array_filter(
            $this->produits,
            fn(Produit $p) => $p->getQuantite() < $seuil
        ));
    }
}