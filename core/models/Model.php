<?php

namespace Models;

abstract class Model
{
    public $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = \DataBase::getPdo();
    }

    /**
     * recupere un element dans la base des donnees 
     *
     * @param int $id
     * @return void
     */
    public function find(int $id): array
    {
        $data = [];
        $sql = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $sql->execute(['id' => $id]);
        $resultat = $sql->fetch();
        if ($resultat) {
            $data[] = $resultat;
        }
        return $data;
    }

    /**
     * recupere tous les données dans une table
     *
     * @param string $order
     * @return array
     */
    public function findBy($statement, string $order = ''): array
    {
        $req = "SELECT * FROM {$this->table}";
        if ($order) {
            $req .= " ORDER BY " . $order;
        }
        $sql = $this->pdo->query($req);
        $resultat = $sql->fetchAll();
        return $resultat;
    }

    public function findOne(string $table): array
    {
        $req = $this->pdo->query("SELECT *from $table");
        $resultat = $req->fetchAll();
        return $resultat;
    }

    /**
     * Enregistrement des donnees
     *
     * @param string $statement
     * @param [type] $variables
     * @return void
     */
    public function persist(string $statment, $variables)
    {
        $req = $this->pdo->prepare($statment);
        $resultat = $req->execute($variables);
        return $resultat;
    }

    public function delete($variables)
    {
        $req = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id=:id");
        $resultat = $req->execute(['id' => $variables]);
        return $resultat;
    }
}