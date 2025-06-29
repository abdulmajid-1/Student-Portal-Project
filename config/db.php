<?PHP
class DatabaseConnectivity
{
    private $connection;

    public function __construct()
    {
        $configuration = parse_ini_file("config.ini");

        $host = $configuration["hostname"];
        $database = $configuration["database"];
        $username = $configuration["username"];
        $password = $configuration["password"];

        $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection()                 
    {
        return $this->connection;
    }
}
?>