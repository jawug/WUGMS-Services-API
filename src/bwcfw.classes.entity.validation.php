<?php

class ValidationVO {

    private $RegexGenericName = "/^([a-zA-Z'\-]{2,20})$/";
    private $RegexPassword = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{6,20}$/';
    private $RegexDateFormat = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
    private $RegexDefaultNameFormat = '/^[A-Za-z0-9()\s]+$/i';
    private $RegexHexColourFormat = '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';
    private $RegexTicketHeader = '/^.{10,100}$/i';
    private $RegexTicketDescription = '/^.{10,4000}$/i';
    private $RegexPostAuthUserName = '/^([a-z0-9]{1,64})$/i';
    private $RegexDomainType = '/^([a-z0-9]{1,10})$/i';
    private $RegexRadCheckOP = '/^(\:\=|\=\=)$/i';

    /**
     *
     * @return String Regex
     */
    public function getRegexGenericName() {
        return $this->RegexGenericName;
    }

    /**
     * 
     * @return String Regex
     */
    public function getRegexPassword() {
        return $this->RegexPassword;
    }

    /**
     * 
     * @return String Regex
     */
    public function getRegexDateFormat() {
        return $this->RegexDateFormat;
    }

    /**
     * 
     * @return String Returns the generic name regex of '/^[A-Za-z0-9()\s]+$/i'
     */
    public function getRegexDefaultNameFormat() {
        return $this->RegexDefaultNameFormat;
    }

    /**
     * 
     * @return String Returns the generic HEX colour checker '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
     */
    public function getRegexHexColourFormat() {
        return $this->RegexHexColourFormat;
    }

    /**
     * 
     * @return String Returns the expression for check the Ticket Header '/^.{10,100}$/i'
     */
    public function getRegexTicketHeader() {
        return $this->RegexTicketHeader;
    }

    /**
     * 
     * @return String Returns the expression to check the ticket Description '/^.{10,4000}$/i'
     */
    public function getRegexTicketDescription() {
        return $this->RegexTicketDescription;
    }

    /**
     * 
     * @return String Returns the expression to check the PostAuth Username '/^([a-z0-9]{1,64})$/i'
     */
    public function getRegexPostAuthUserName() {
        return $this->RegexPostAuthUserName;
    }

    /**
     * 
     * @return String Returns the expression to check the Domain Type '/^([a-z0-9]{1,10})$/i'
     */
    public function getRegexDomainType() {
        return $this->RegexDomainType;
    }

    /**
     * 
     * @return String Returns the expression to check the RadCheck OP Type '/^(\:\=|\=\=)$/i'
     */
    public function getRegexRadCheckOP() {
        return $this->RegexRadCheckOP;
    }

}

class entity_validation {

    /**
     *
     * @var String This is the regex used for the checking of the date format YYYY-MM-DD 
     */
    private $DateRegex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";

    /**
     * 
     * @param datetime $date This checks that the parameter supplied is a valid date
     * @return datetime
     * @throws Exception
     */
    public function validateDate($date) {
        if (!preg_match($this->DateRegex, $date)) {
            throw new Exception('Date is wrong format');
        }
        return $date;
    }

    /**
     * 
     * @param interger $value 
     * @return type boolean
     * @throws Exception
     */
    public function validateCheckNumeric($value) {
        if (!is_numeric($value)) {
            throw new Exception('Value is not numeric');
        }
        return $value;
    }

    public function validateStringNotEmpty($value) {
        if (empty($value)) {
            throw new Exception('String is empty');
        }
        return $value;
    }

    /**
     * 
     * @param string $value
     * @param integer $length
     * @return string
     * @throws Exception
     */
    public function validateStringLength($value, $length) {
        if ((strlen($value) > $length) || (strlen($value) < $length)) {
            throw new Exception('Value is incorrect length');
        }
        return $value;
    }

    /**
     * 
     * @param integer $number
     * @param integer $min
     * @param integer $max
     * @return boolean
     * @throws Exception
     */
    public function validateNumberBTV($number, $min, $max) {
        if ($number >= $min && $number <= $max) {
            return TRUE;
        } else {
            throw new Exception('Value is out of bounds');
        }
    }

    /**
     * This function checks to see if $startdate is greater $enddate and if that is the case then fails
     * 
     * @param datetime $startdate
     * @param datetime $enddate
     * @return boolean
     * @throws Exception
     */
    public function StartDateLTTEndDate($startdate, $enddate) {
        if ($startdate > $enddate) {
            throw new Exception('Start Date greater than End Date');
        }
        return TRUE;
    }

    /**
     * 
     * @param string $value
     * @param string $regex
     * @return type
     * @throws Exception
     */
    public function validateStringRegex($value, $regex) {
        if (!preg_match($regex, $value)) {
            throw new Exception('String value does not match Regex: ' . $regex);
        }
        return $value;
    }

    public function validateStringLengthMinMax($value, $min, $max) {
        $i = strlen($value);
        if ($i < $min || $i > $max) {
            throw new Exception('String value does not meet min/max requirements');
        }
        return $value;
    }

    /**
     * 
     * @param string $first
     * @param string $second
     * @return boolean
     * @throws Exception
     */
    public function validateStringUnique($first, $second) {
        if (strtoupper($first) === strtoupper($second)) {
            throw new Exception('Values are the same');
        }
        return TRUE;
    }

    /**
     * 
     * @param string $first
     * @param string $second
     * @return boolean
     * @throws Exception
     */
    public function validateStringSame($first, $second) {
        if (strtoupper($first) !== strtoupper($second)) {
            throw new Exception('Values are not the same');
        }
        return TRUE;
    }

    /**
     * 
     * @param string $email
     * @return type
     * @throws Exception
     */
    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('String value is not valid email');
        }
        return $email;
    }

    /**
     *
     * @var \ValidationVO 
     */
    var $expressions;

    public function __construct() {
        $this->expressions = new ValidationVO();
    }

}
