<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Compra
 *
 * @ORM\Table(name="compras", indexes={@ORM\Index(name="FK_compras_billetera_id", columns={"billetera_id"})})
 * @ORM\Entity
 */
class Compra
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
     * @ORM\Column(name="billetera_id", type="integer", nullable=false)
     */
    private $billeteraId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subtotal", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $subtotal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iva", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $iva;

    /**
     * @var string|null
     *
     * @ORM\Column(name="total", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $total;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $fecha = 'CURRENT_TIMESTAMP';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;


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
     * Set billeteraId.
     *
     * @param int $billeteraId
     *
     * @return Compras
     */
    public function setBilleteraId($billeteraId)
    {
        $this->billeteraId = $billeteraId;

        return $this;
    }

    /**
     * Get billeteraId.
     *
     * @return int
     */
    public function getBilleteraId()
    {
        return $this->billeteraId;
    }

    /**
     * Set subtotal.
     *
     * @param string|null $subtotal
     *
     * @return Compras
     */
    public function setSubtotal($subtotal = null)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal.
     *
     * @return string|null
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set iva.
     *
     * @param string|null $iva
     *
     * @return Compras
     */
    public function setIva($iva = null)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva.
     *
     * @return string|null
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set total.
     *
     * @param string|null $total
     *
     * @return Compras
     */
    public function setTotal($total = null)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return string|null
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Compras
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set status.
     *
     * @param bool|null $status
     *
     * @return Compras
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool|null
     */
    public function getStatus()
    {
        return $this->status;
    }
}
