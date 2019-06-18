<?php
require __DIR__ . '/Logger.php';
class Tranche
{
    public $maxAmt;
    public $interest;
    public $amount=0;
    public $date;
    public $loan;
    public $name;
    public $investments=array();
    public $log;

    public function __construct($name, $interest)
    {
        $this->maxAmt=1000;
        $this->interest=$interest;
        $this->log= new Logger();
    }

    public function investLoan($amt, $date, $tranche)
    {
    }

    public function setLoan($loan)
    {
        $this->loan=$loan;
    }


    public function investAmount($amt, $date, $investor)
    {
        $investmentApproved=true;

        if ($date<$this->loan->start_date || $date>$this->loan->end_date) {
            $investmentApproved=false;
            $this->log->addLog("Investment date out of range", 'Error');
            throw new Exception("Date out of range", 200);
        }

        $totalamt=$amt;
        if (!empty($this->investments)) {
            foreach ($this->investments as $investment) {
                $totalamt=$totalamt+$investment['amt'];
            }
        }
        

        if ($totalamt>$this->maxAmt) {
            $investmentApproved=false;
            $this->log->addLog("Cannot invest amount to this Loan Tranche", 'Error');
            throw new Exception("Amount invested exceeds Max Loan Amount", 100);
        }

        if ($investmentApproved) {
            $this->investments[]=array('amt'=>$amt,'date'=>$date,'investor'=>$investor);
            $this->log->addLog("Investment Added", 'OK');
            return "OK";
        }
    }
}
