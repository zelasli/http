<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http\Message;

/**
 * Representation of an incoming HTTP message (Server-side) request.
 *
 * The ServerRequestInterface instance are immutable.
 */
interface ServerRequestInterface extends RequestInterface {
  /**
   * Retrieve query parameters
   *
   * @return array
   */
  public function getQueryParams(): array;

  /**
   * Retrieve server parameters
   *
   * @return array
   */
  public function getServerParams(): array;

  /**
   * Retrieve environment variables
   *
   * @return array
   */
  public function getEnvParams(): array;

  /**
   * Retrieve cookie parameters
   *
   * @return array
   */
  public function getCookieParams(): array;

  /**
   * Retrieve session parameters
   *
   * @return array
   */
  public function getSessionParams(): array;

  /**
   * Retrieve uploaded files data
   *
   * @return array
   */
  public function getUploadedFiles(): array;

  /**
   * Retrieve parsed request body data
   *
   * @return array|null|object
   */
  public function getParsedBody();
}
