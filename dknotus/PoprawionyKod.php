<?php

class PoprawionyKod
{
    /*rozumiem ze te pola sa wykorzystywane w innych metodach*/
    private static $descGetPostfix = "Text";
    protected $eventKindMapping = false;
    protected $tableName = false;
    protected $data = null;
    protected $rowId = false;
    protected $primaryKeyName = false;
    protected $activeColName = false;
    protected $logCols = false;
    protected $nameMapping = false;
    protected $storageHandling = 'standard';
    protected $dateFromColName = "data_od";
    protected $dateToColName = "data_do";
    private $eventKing;
    private $eventDesc;

    public function __construct()
    {
        /*lub jesli istnieje juz konstruktor to dodac do niego*/
        $this->data = new stdClass();
    }

    /**
     * @param bool $eventKind
     * @param bool $eventDesc
     * @return bool
     */
    public function delete($eventKind = false, $eventDesc = false)
    {
        /*dodanie jako wlasciwosci, zamiast przekazywanie ciagle przez parametry*/
        $this->eventKing = $eventKind;
        $this->eventDesc = $eventDesc;

        $dataBeforeProcessing = clone $this->data;

        $this->removeTextKey();

        /*przeniesienie do osobnej metody dla czytelnosci*/
        $this->storageHandling();

        $this->data = (array)$dataBeforeProcessing;
        return true;
    }

    private function removeTextKey()
    {
        foreach ($this->data as $key => $d) {
            if (strpos($key, '_text') || strpos($key, '_external')) {
                unset($this->data->{$key});
            }
        }
    }


    private function storageHandling()
    {
        try {
            /*rozbicie na switcha i poszczegolne metody - dla czytelnosci
            w nazwach uzyte 'doSmth' bo ciezko mi stwierdzic co robia dokladnie*/
            switch ($this->storageHandling) {
                case 'standard':
                    $this->doSmthStandard();
                    break;
                case 'history':
                    $this->doSmthHistory();
                    break;
                case 'versioned':
                    $this->doSmthVersioned();
                    break;
                case 'version':
                    $this->doSmthVersion();
                    break;
            }
        } catch (Exception $e) {
            throw new Softer_Exception($e, 1);
        }
        /*byl jeszcze bledny else z rzuceniem wyjatku ktory zostal usuniety*/
    }

    private function doSmthStandard()
    {
        $this->setActive('N');
        $this->dbUpdate([$this->activeColName => $this->getActive()]);
        /*saveLog moglby korzytac z wlasciwosci, a nie z danych z parametru, aby i ch ciagle nie przekazywac*/
        $this->saveLog($this->eventKing, $this->eventDesc);
    }

    private function doSmthHistory()
    {
        $this->dbUpdate([$this->dateToColName => date("Y-m-d H:i:s")]);
        $this->saveLog($this->eventKing, $this->eventDesc);
    }

    private function doSmthVersioned()
    {
        $this->setActive('N');
        $this->dbUpdate((array)$this->data);
        $_version = get_class($this) . 'Version';
        /*tylko po to zeby wywolac __construct? moze dzieje sie tam cos ciekawego ;) */
        new $_version($this);
        $this->saveLog($this->eventKing, $this->eventDesc);
    }

    private function doSmthVersion()
    {
        /*chyba powinien przekazywac tablice, a nie obiekt, wiec dodane rzutowanie*/
        $this->dbUpdate((array)$this->data);
        /*tutaj nic nie logujemy?
        jesli tak to mozna saveLog przeniesc do metody dbUpdate*/
    }

    /**
     * @param array $aDateToColName
     */
    private function dbUpdate(array $aDateToColName)
    {
        $this->_db->update(
            $this->tableName,
            $aDateToColName,
            $this->primaryKeyName . ' = ' . $this->getRowId()
        );
    }

}

$class = new PoprawionyKod();
var_dump($class->delete());