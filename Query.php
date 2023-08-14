<?php


class Query
{
    /**
     * query
     * @var string
     */
    protected $db_qry;

    /**
     * start date
     * @var string
     */
    protected $db_start_date;

    /**
     * end date
     * @var string
     */
    protected $db_end_date;

    /**
     * Constructor
     *
     * @param string $qry           Hostname
     * @param string $start_date    Date start period
     * @param string $end_date      Date end period
     *
     * @return void
     */
    /**public function __construct($qry, $start_date, $end_date)
    {
        $this->db_qry = $qry;
        $this->db_start_date = $start_date;
        $this->db_end_date = $end_date;
    }    
    */
  
    public function getAll($conn)
    {
        $sql = "SELECT *
                FROM incomes
                ";

        $stmt = $conn->prepare($sql);
        //$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function pushSomeData($conn)
    {
        $sql = "INSERT INTO incomes 
        VALUES(5, 5, 'koks', 1000, '12-02-2000','anything')";

        $stmt = $conn->prepare($sql);
        //$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();
    }
}
