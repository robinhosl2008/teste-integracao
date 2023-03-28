<?php

namespace BoasPraticas\Leilao\Leilao\Tests;

use BoasPraticas\Leilao\Model\Leilao;
use BoasPraticas\Leilao\Model\Lance;
use BoasPraticas\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    private $leilao;
    private $usuario;
    private $newUser;

    public function setUp(): void
    {
        $this->leilao = new Leilao("Fiat 147 0Km");
        $this->usuario = new Usuario("Usuario1");
        $this->leilao->recebeLance(new Lance($this->usuario, 1000));
    }

    public function testVerificarSeLeilaoEstaSendoCriado()
    {
        self::assertEquals("object", gettype($this->leilao));
    }

    public function testVerificaSeLeilaoRecebeuDescricao()
    {
        self::assertEquals("Fiat 147 0Km", $this->leilao->recuperaDescricao());
    }

    public function testVerificaSeLeilaoRecebeuLance()
    {
        $lances = $this->leilao->recuperaLances();
        
        self::assertArrayHasKey(0, $lances);
        self::assertEquals("object", gettype($lances[0]));
    }

    public function testVerificaQueLeilaoNaoRecebaMaisDeUmLance()
    {
        $novoLance = new Lance($this->usuario, 1200);
        $this->leilao->recebeLance($novoLance);

        self::assertCount(1, $this->leilao->recuperaLances());
    }

    public function testNaoPermitirMaisDeCincoLancesPorUsuarioNoMesmoLeilao()
    {
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 1500));
        $this->leilao->recebeLance(new Lance($this->usuario, 2000));
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 2500));
        $this->leilao->recebeLance(new Lance($this->usuario, 3000));
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 3500));
        $this->leilao->recebeLance(new Lance($this->usuario, 4000));
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 4500));
        $this->leilao->recebeLance(new Lance($this->usuario, 5000));
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 5500));
        $this->leilao->recebeLance(new Lance($this->usuario, 6000));
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 6500));
        $this->leilao->recebeLance(new Lance($this->usuario, 7000));
        $this->leilao->recebeLance(new Lance(new Usuario("usuario2"), 7500));
        
        self::assertCount(10, $this->leilao->recuperaLances());
        self::assertEquals(5500, $this->leilao->recuperaLances()[array_key_last($this->leilao->recuperaLances())]->recuperaValor());
    }
}