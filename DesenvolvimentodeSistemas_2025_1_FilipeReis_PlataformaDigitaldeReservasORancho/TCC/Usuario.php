<?php
class Usuario {
    public $nome;
    public $email;
    public $telefone;
    public $cpf;
    public $endereco;
    public $dataNascimento;
    public $senha;

    public function __construct($nome, $email, $telefone, $cpf, $endereco, $dataNascimento, $senha) {
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->endereco = $endereco;
        $this->dataNascimento = $dataNascimento;
        $this->senha = $senha;
    }
}
?>