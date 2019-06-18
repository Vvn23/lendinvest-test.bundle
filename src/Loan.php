<?php
class Loan
{
    public $start_date;
    public $end_date;
    public $id;
    public $tranches=array();

    public function __construct($startdate, $enddate)
    {
        $this->start_date=$startdate;
        $this->end_date=$enddate;
        $this->id='12345';
    }

    public function getLoanId()
    {
        return $this->id;
    }

    public function addTranches($tranche)
    {
        $this->tranches[]=$tranche;
    }


    public function calculateInterest($sDate, $eDate)
    {
        $interestPeriod= ceil(abs($eDate - $sDate) / 86400)+1;
        $investedRecords=array();
        foreach ($this->tranches as $tranche) {
            $interest=array();
            foreach ($tranche->investments as $investment) {
                if ($investment['date']>$sDate && $investment['date']<$eDate) {
                    $totalDays= ceil(abs($eDate - $investment['date']) / 86400)+1;
                    $monthlyInterest=$investment['amt']*$tranche->interest/100;
                    $interestPerDay=$monthlyInterest/$interestPeriod;
                    $totalInterest=round($interestPerDay*$totalDays, 2);
                    $investedRecords[]=$investment + array('interest'=>$totalInterest);
                }
            }
        }
        return $investedRecords;
    }
}
