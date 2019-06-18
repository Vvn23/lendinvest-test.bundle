<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__. '/../src/Loan.php';
require __DIR__. '/../src/Tranche.php';
class InvestmentTest extends PHPUnit_Framework_TestCase
{
    protected $loan;
    protected $tranche1;
    protected $tranche2;

    public function setUp()
    {
        $stDate=  strtotime('01-10-2015');
        $edDate= strtotime('15-11-2015');
        $this->loan = new Loan($stDate, $edDate);
        $this->tranche1= new Tranche('A', 3);
        $this->tranche1->setLoan($this->loan);
        $this->loan->addTranches($this->tranche1);
        $this->tranche2= new Tranche('B', 6);
        $this->tranche2->setLoan($this->loan);
        $this->loan->addTranches($this->tranche2);
    }

    public function testInvestAcceptedAmount()
    {
        $investor1 = 'A';
        $date= strtotime('03-10-2015');
        $this->assertEquals("OK", $this->tranche1->investAmount(1000, $date, $investor1));
    }


    public function testInvestFailAmount()
    {
        try {
            $investor1 = 'A';
            $investor2 = 'B';
            $date= strtotime('03-10-2015');
            $this->tranche1->investAmount(1000, $date, $investor1);
            $date2=strtotime('04-10-2015');
            $this->tranche1->investAmount(1, $date2, $investor2);
            $this->fail("Expected Exception has not been raised.");
        } catch (Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Amount invested exceeds Max Loan Amount");
        }
    }

    public function testTrancheBAmountAccepted()
    {
        $investor3 = 'Investor 3';
        $date= strtotime('10-10-2015');
        $this->assertEquals("OK", $this->tranche2->investAmount(500, $date, $investor3));
    }


    public function testTrancheBFailedAmount()
    {
        try {
            $investor3 = 'Investor 3';
            $investor4 = 'Investor 4';
            $date= strtotime('04-10-2015');
            $this->tranche2->investAmount(500, $date, $investor3);
            $date2=strtotime('25-10-2015');
            $this->tranche2->investAmount(1100, $date2, $investor4);
            $this->fail("Expected Exception has not been raised.");
        } catch (Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Amount invested exceeds Max Loan Amount");
        }
    }

    public function testInvalidDate()
    {
        try {
            $investor3 = 'Investor 3';
            $date= strtotime('24-12-2015');
            $this->tranche2->investAmount(500, $date, $investor4);
            $this->fail("Expected Exception has not been raised.");
        } catch (Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Date out of range");
        }
    }


    public function testCalculateInterest()
    {
        $investor1 = 'Investor 1';
        $date= strtotime('03-10-2015');
        $this->tranche1->investAmount(1000, $date, $investor1);

        $investor3 = 'Investor 3';
        $date= strtotime('10-10-2015');
        $this->tranche2->investAmount(500, $date, $investor3);

        $startdate=strtotime('01-10-2015');
        $enddate=strtotime('31-10-2015');
        $interests=$this->loan->calculateInterest($startdate, $enddate);

        $investor_1=$interests[0]['investor'].': earns '.$interests[0]['interest'].' pounds';
        $investor_3=$interests[1]['investor'].': earns '.$interests[1]['interest'].' pounds';
        $this->assertEquals("Investor 1: earns 28.06 pounds", $investor_1);
        $this->assertEquals("Investor 3: earns 21.29 pounds", $investor_3);
    }
}
