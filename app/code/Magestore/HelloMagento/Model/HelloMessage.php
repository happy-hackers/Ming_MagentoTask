<?php
namespace Magestore\HelloMagento\Model;
class HelloMessage
{
      /**
     * this function returns a hello word string
     * @return string
     */
    public function getMessage()
    {
        return 'Hello Magento 2 :)) blalala';
    }

    /**
     * this function returns the result of the addition of two numbers
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

  
}