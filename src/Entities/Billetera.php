<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Billetera
 *
 * @ORM\Table(name="billeteras", indexes={@ORM\Index(name="FK_billeteras_documento", columns={"documento_id"})})
 * @ORM\Entity
 */
class Billetera
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="documento_id", type="integer", nullable=false)
     */
    private $documentoId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="saldo", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $saldo;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set documentoId.
     *
     * @param int $documentoId
     *
     * @return Billeteras
     */
    public function setDocumentoId($documentoId)
    {
        $this->documentoId = $documentoId;

        return $this;
    }

    /**
     * Get documentoId.
     *
     * @return int
     */
    public function getDocumentoId()
    {
        return $this->documentoId;
    }

    /**
     * Set saldo.
     *
     * @param string|null $saldo
     *
     * @return Billeteras
     */
    public function setSaldo($saldo = null)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo.
     *
     * @return string|null
     */
    public function getSaldo()
    {
        return $this->saldo;
    }
}
