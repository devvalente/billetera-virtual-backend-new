<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TokenPago
 *
 * @ORM\Table(name="token_pagos", uniqueConstraints={@ORM\UniqueConstraint(name="UC_token_pagos_token", columns={"token"})}, indexes={@ORM\Index(name="FK_token_pagos_compra", columns={"compra_id"}), @ORM\Index(name="FK_token_pagos_billetera", columns={"billetera_id"})})
 * @ORM\Entity
 */
class TokenPago
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=6, nullable=false)
     */
    private $token;

    /**
     * @var int
     *
     * @ORM\Column(name="compra_id", type="integer", nullable=false)
     */
    private $compraId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiracion", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $expiracion = 'CURRENT_TIMESTAMP';


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
     * @return TokenPagos
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
     * Set email.
     *
     * @param string $email
     *
     * @return TokenPagos
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token.
     *
     * @param string $token
     *
     * @return TokenPagos
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set compraId.
     *
     * @param int $compraId
     *
     * @return TokenPagos
     */
    public function setCompraId($compraId)
    {
        $this->compraId = $compraId;

        return $this;
    }

    /**
     * Get compraId.
     *
     * @return int
     */
    public function getCompraId()
    {
        return $this->compraId;
    }

    /**
     * Set expiracion.
     *
     * @param \DateTime $expiracion
     *
     * @return TokenPagos
     */
    public function setExpiracion($expiracion)
    {
        $this->expiracion = $expiracion;

        return $this;
    }

    /**
     * Get expiracion.
     *
     * @return \DateTime
     */
    public function getExpiracion()
    {
        return $this->expiracion;
    }
}
