<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http\Message;

/**
 * Representation of an HTTP Message Interface
 *
 * The MessageInterface provide abstraction to HTTP Message that could be a
 * requests from a client to serve, and/or responses from the server to the
 * client
 *
 * @link https://datatracker.ietf.org/doc/rfc7230 
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Messages
 */
interface MessageInterface
{
  /**
   * Get HTTP message start line
   * 
   * @return array
   */
  public function getStartLine(): array;

  /**
   * Get HTTP message start line string
   * 
   * @return string
   */
  public function getStartLineString(): string;

  /**
   * Get HTTP protocol version
   *
   * Retrieves the HTTP prototcol version string. The version MUST contain
   * only the HTTP version number only (e.g. "1.0", "1.1")
   *
   * @return string returns the message protocol version
   */
  public function getProtocolVersion(): string;

  /**
   * Get all HTTP message headers
   *
   * Retrieve all the message headers for as key => value pairs. The
   * represent the header name as string type and value represent an
   * array of string values that are associated with header name.
   *
   * @return array returns the list of all headers
   */
  public function getHeaders(): array;

  /**
   * Get header
   *
   * Retrieve the header by the given name. The name is case-insensitive
   *
   * @param string $name of header
   *
   * @return array returns the header if matched empty otherwise.
   */
  public function getHeader(string $name): array;

  /**
   * Get header value
   *
   * @param string $name
   *
   * @return string
   */
  public function getHeaderLine(string $name): string;

  /**
   * Check whether header exists by the given name. The name is 
   * case-insensitive
   *
   * @param string $name
   *
   * @return bool returns true if header name matched the names in the 
   * message headers false otherwise
   */
  public function hasHeader(string $name): bool;

  /**
   * Get the message body
   *
   * @return StreamInterface|null returns StreamInterface if there is
   * body null otherwise
   */
  public function getBody(): ?StreamInterface;
}
