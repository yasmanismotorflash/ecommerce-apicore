<?php
namespace App\Tools;

/***
* Cliente FTP
* Interfas simple de cliente FTP para usar desde php de manera simple en aplicaciones,
* para  hacer uso del servicio de ftp y sftp.
* Version: 1.0
* Programador: Eidy Estupiñan Varona <eidyev@gmail.com>
* Requirimientos: Extencion php_ftp, libreria phpseclib/phpseclib,
* puede ser instalada desde composer "composer require phpseclib/phpseclib"
*/

use phpseclib3\Net\SFTP;

class SimpleSFTPClient
{
    private string $protocol; // 'ftp' o 'sftp'
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private $connection;

    public function configure(string $protocol, string $host, int $port, string $username, string $password):SimpleSFTPClient
    {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        return $this;
    }


    public function connect():bool
    {
        if (strtolower($this->protocol) === 'sftp')
        {
            $this->connection = new SFTP($this->host, $this->port);
            if (!$this->connection->login($this->username, $this->password))
                throw new \Exception("Error al conectar por SFTP");
            return true;
        }
        elseif (strtolower($this->protocol) === 'ftp') {
            $this->connection = ftp_connect($this->host, $this->port);
            if (!$this->connection || !ftp_login($this->connection, $this->username, $this->password)) {
                throw new \Exception("Error al conectar por FTP");
            }
            ftp_pasv($this->connection, true); // Activa el modo pasivo
            return true;
        }
        return false;
    }

    public function disconnect(): SimpleSFTPClient
    {
        if ($this->protocol === 'sftp' && $this->connection) {
            $this->connection->disconnect();
        } elseif ($this->protocol === 'ftp' && $this->connection) {
            ftp_close($this->connection);
        }
        return $this;
    }


    public function fileExist(string $file): bool
    {
        if ($this->protocol === 'sftp') {
            return $this->connection?->file_exists($file);
        } elseif ($this->protocol === 'ftp') {
            $files = ftp_nlist($this->connection, dirname($file));
            return in_array(basename($file), $files);
        }
        return false;
    }


    public function downloadFile(string $remoteFile, string $localFile, bool $progress = false)
    {
        if ($this->protocol === 'sftp') {
            if ($progress) {
                $filesize = $this->connection->filesize($remoteFile);
                $stream = fopen($localFile, 'w');
                $this->connection->get($remoteFile, function ($data) use ($stream, $filesize) {
                    static $written = 0;
                    fwrite($stream, $data);
                    $written += strlen($data);
                    printf("\rProgreso: %2d%%", ($written / $filesize) * 100);
                });
                fclose($stream);
            } else {
                $this->connection->get($remoteFile, $localFile);
            }
        } elseif ($this->protocol === 'ftp') {
            if ($progress) {
                $filesize = ftp_size($this->connection, $remoteFile);
                $stream = fopen($localFile, 'w');
                $ret = ftp_nb_get($this->connection, $localFile, $remoteFile, FTP_BINARY);
                while ($ret == FTP_MOREDATA) {
                    $written = ftell($stream);
                    printf("\rProgreso: %2d%%", ($written / $filesize) * 100);
                    $ret = ftp_nb_continue($this->connection);
                }
                fclose($stream);
                if ($ret != FTP_FINISHED) {
                    throw new \Exception("Error al descargar el archivo por FTP");
                }
            } else {
                if (!ftp_get($this->connection, $localFile, $remoteFile, FTP_BINARY)) {
                    throw new \Exception("Error al descargar el archivo por FTP");
                }
            }
        }
        return $this;
    }

    public function uploadFile(string $localFile, string $remoteFile, bool $progress = false):SimpleSFTPClient
    {
        if ($this->protocol === 'sftp') {
            if ($progress) {
                $filesize = filesize($localFile);
                $stream = fopen($localFile, 'r');
                $this->connection->put($remoteFile, function ($length) use ($stream, $filesize) {
                    static $read = 0;
                    $data = fread($stream, $length);
                    $read += strlen($data);
                    printf("\rProgreso: %2d%%", ($read / $filesize) * 100);
                    return $data;
                }, SFTP::SOURCE_CALLBACK);
                fclose($stream);
            } else {
                $this->connection->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE);
            }
        } elseif ($this->protocol === 'ftp') {
            if ($progress) {
                $filesize = filesize($localFile);
                $stream = fopen($localFile, 'r');
                $ret = ftp_nb_put($this->connection, $remoteFile, $localFile, FTP_BINARY);
                while ($ret == FTP_MOREDATA) {
                    $read = ftell($stream);
                    printf("\rProgreso: %2d%%", ($read / $filesize) * 100);
                    $ret = ftp_nb_continue($this->connection);
                }
                fclose($stream);
                if ($ret != FTP_FINISHED) {
                    throw new \Exception("Error al subir el archivo por FTP");
                }
            } else {
                if (!ftp_put($this->connection, $remoteFile, $localFile, FTP_BINARY)) {
                    throw new \Exception("Error al subir el archivo por FTP");
                }
            }
        }
        return $this;
    }



    public function listRemotePath(string $ruta): array
    {
        if ($this->protocol === 'sftp') {
            return $this->connection->nlist($ruta);
        } elseif ($this->protocol === 'ftp') {
            return ftp_nlist($this->connection, $ruta);
        }
        return [];
    }

    // Getters y setters ...

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return SimpleSFTPClient
     */
    public function setProtocol(string $protocol): SimpleSFTPClient
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return SimpleSFTPClient
     */
    public function setHost(string $host): SimpleSFTPClient
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return SimpleSFTPClient
     */
    public function setPort(int $port): SimpleSFTPClient
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return SimpleSFTPClient
     */
    public function setUsername(string $username): SimpleSFTPClient
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return SimpleSFTPClient
     */
    public function setPassword(string $password): SimpleSFTPClient
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     * @return SimpleSFTPClient
     */
    public function setConnection($connection): SimpleSFTPClient
    {
        $this->connection = $connection;
        return $this;
    }
}


/*
// Ejemplo de uso
// Ejemplo de uso
try {
    $cliente = new SimpleSFTPClient();
    $cliente->configure('sftp', 'host', 22, 'username', 'password');
    $cliente->connect();

    $archivo = 'path/to/file.txt';
    if ($cliente->fileExist($archivo)) {
        echo "El archivo existe.\n";
        $cliente->downloadFile($archivo, 'local/path/file.txt', true);
    } else {
        echo "El archivo no existe.\n";
    }

    // Para subir un archivo con progreso
    $cliente->uploadFile('local/path/file.txt', 'path/to/upload/file.txt', true);

    // Listar contenido de una ruta
    $ruta = 'path/to/directory';
    $contenido = $cliente->listRemotePath($ruta);

    print_r($contenido);

    $cliente->disconnect();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

*/
