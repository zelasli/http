<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http\Message;

use Zelasli\Http\UriInterface;

/**
 * Representation of on outgoing HTTP message (Client-side) request.
 *
 * The RequestInterface is an abstraction to HTTP Message that could be a
 * request from client to server
 */
interface RequestInterface extends MessageInterface {
  /**
   * Get HTTP URI
   *
   * Retrieve the HTTP Uniform Resource Identifier (URI)
   *
   * @return \Zelasli\Http\UriInterface
   */
  public function getURI(): UriInterface;

  /**
   * Get the HTTP request's method
   *
   * @return string
   */
  public function getMethod(): string;

  /**
   * Get the HTTP messages's request target
   *
   * @return string
   */
  public function getRequestTarget(): string;
}
