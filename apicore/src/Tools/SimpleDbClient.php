<?php
namespace App\Tools;

/***
 * Cliente simple de Base de datos
 * Interfas simple de cliente de base de datos para usar desde php de manera simple en aplicaciones,
 * Version: 1.0
 * Programador: Eidy Estupiñan Varona <eidyev@gmail.com>
 * Requerimientos: Extención de php para mysql (php_mysqli)
 */
class SimpleDbClient
{
    private $conexion = null;
    private string $host;
    private string $user;
    private string $password;
    private string $dataBase;
    private int $port;

    public function configure(string $host, string $user, string $password, string $dataBase, int $port)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dataBase = $dataBase;
        $this->port = $port;
        $this->conexion = null;
    }

    //Conectar con la base de datos y mantener la conexion activa.
    public function connectBD(): void
    {
        //Ver sino está inicializada la conexion
        if(!$this->conexion || $this->conexion->ping() === false)
        {   /*** @var \mysqli  $this->conexion */
            $this->conexion = new \mysqli($this->host, $this->user, $this->password, $this->dataBase, $this->port);
            $this->conexion->options(MYSQLI_OPT_LOCAL_INFILE, true);
        }

        if ($this->conexion->connect_error) {
            throw new \Exception("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    //Desconectar la conexion activa con la base de datos.
    public function disconnectBD(): void
    {
        $this->conexion->close();
    }


    /**
     * Ejecuta una consulta SQL y devuelve los resultados en un array asociativo.
     * @param string $query La consulta SQL a ejecutar.
     * @return array|null Los datos obtenidos en un array asociativo o null en caso de error.
     */
    public function executeQuery(string $query): ?array
    {
        $this->connectBD();
        $result = $this->conexion->query($query);

        if ($result === false) {
            throw new \Exception("Error en la consulta: " . $this->conexion->connect_error);
        }

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }


    /**
     * Ejecuta una consulta SQL sin esperar resultados.
     * @param string $query La consulta SQL a ejecutar.
     * @return bool true si la consulta fue exitosa, false en caso de error.
     */
    public function executeQueryNoResult(string $query): bool
    {
        $this->connectBD();
        $resultado = $this->conexion->query($query);

        if ($resultado === false) {
            throw new \Exception("Error en la consulta: " . $this->conexion->connect_error);
        }
        return true;
    }


    /**
     * Ejecuta un archivo SQL.
     * @param string $archivoSQL La ruta al archivo SQL.
     * @return bool True si se ejecutó correctamente, false en caso contrario.
     */
    public function executeSQLfile(string $archivoSQL): bool
    {
        try {
            if(!$this->conexion)
                $this->connectBD();

            $consultasSQL = file_get_contents($archivoSQL);

            if ($consultasSQL === false) {
                return false;
            }

            if ($this->conexion->multi_query($consultasSQL)) {
                do {
                    if ($result = $this->conexion->store_result()) {
                        $result->free();
                    }
                } while ($this->conexion->more_results() && $this->conexion->next_result());
                return true;
            } else {
                echo "Error al ejecutar el script SQL: " . $this->conexion->error;
                return false;
            }
        } catch (\Exception $e) {
            echo "Error al ejecutar el archivo SQL: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Verifica si una tabla existe en la base de datos.
     * @param string $nombreTabla El nombre de la tabla a verificar.
     * @return bool True si la tabla existe, false en caso contrario.
     */
    public function tableExist(string $nombreTabla): bool
    {
        $this->connectBD();
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = ?");
        $stmt->bind_param('ss', $this->dataBase, $nombreTabla);
        $stmt->execute();
        $existe='';
        $stmt->bind_result($existe);
        $stmt->fetch();
        $stmt->close();
        return $existe > 0;
    }


}
