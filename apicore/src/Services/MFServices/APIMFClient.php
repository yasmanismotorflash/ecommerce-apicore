<?php

namespace App\Services\MFServices;
use App\Services\Comun\Configuration;

/**
 * Servicio cliente para APIMF
 * @author Eidy Estupiñan Varona <eidy.estupinan@motorflash.com>
 */
class APIMFClient
{
    private string $apiMfUrl;
    private string $apiMfClientId;
    private string $apiMfClientSecret;


    private ?string $token = null;


    public function __construct(Configuration $config)
    {
        $this->apiMfUrl = $config->getParameter('API-MF-URL');
        $this->apiMfClientId = $config->getParameter('API-MF-CLIENT-ID');
        $this->apiMfClientSecret = $config->getParameter('API-MF-CLIENT-SECRET');

        $this->token = $this->getToken();
    }


    /**
     *  Función para autenticación y obtener token de acceso al APIMF,
     * mientras el token sea válido usa el actual, sino pide uno nuevo
     */
    public function getToken(): void
    {
        if (null === $this->token || !$this->checkTokenExpired()) {
            //TODO - Implementar autenticacion contra apimf
            $token = 'Autenticarse contra apimf';
            $this->token = $token;
        }
    }

    /**
     * Funcion para verificar la validez del token de autenticación
     * @return bool true si está expirado, false si todavía es válido
     */
    public function checkTokenExpired(): bool
    {
        if (null === $this->token) {
            return false;
        }
        //TODO decodificar token y ver si es valido todavia antes de pedir uno nuevo
    }


    /**
     * Función para obtener un anuncio especificando el id de motorflash ad_id
     * @param int $ad_id id de anuncio en motorflash
     * @return array  un objeto de tipo anuncio o null
     */
    public function getAdsByMfId(int $ad_id): ?array
    {

        return null;
    }


    /**
     * Función para obtener un anuncio especificando el Vehicle Idintification Number (VIN)
     * @param int $VIN del de anuncio
     * @return array  un objeto de tipo anuncio o null
     */
    public function getAdByVIN(int $VIN): ?array {

        return null;
    }









}