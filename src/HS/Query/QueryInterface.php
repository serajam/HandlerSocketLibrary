<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

interface QueryInterface
{
    /**
     * @return \HS\Result\ResultInterface|\HS\Result\InsertResult|\HS\Result\ModifyResultAbstract
     */
    public function getResult();

    /**
     * @return string
     */
    public function getQueryString();

    /**
     * @param string $data
     *
     * @return void
     */
    public function setResultData($data);

    /**
     * @return int
     */
    public function getIndexId();

    /**
     * @return $this
     * @throws \HS\Exception\InvalidArgumentException
     */
    public function execute();
} 