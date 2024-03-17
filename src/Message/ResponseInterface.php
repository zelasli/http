<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http\Message;

/**
 * Representation of an outgoing response
 */
interface ResponseInterface {
  /**
   * Gets the response status code
   *
   * @return int
   */
  public function getStatusCode(): int;

  /**
   * Change the response status code
   *
   * @return self
   */
  public function setStatusCode(): self;

  /**
   * Gets the response reason phrase for the status code
   *
   * @return string
   */
  public function getReasonPhrase(): string;

  /**
   * Change the response reason phrase for the status code
   *
   * @return self
   */
  public function setReasonPhrase(): self;
}
