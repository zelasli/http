<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http;

/**
 * Representation of URI interface
 *
 * The UriInterface represents URIs according to RFC 3986: Generic syntax
 *
 * @link https://datatracker.ietf.org/doc/rfc3986
 * @package Zelasli\Http
 */
interface UriInterface
{
  /**
   * Retrieve the URL scheme
   *
   * Return empty string if no scheme is present
   *
   * @return string
   */
  public function getScheme(): string;

  /**
   * Retrieve an array the URI authority
   *
   * Return null if no user information and host is present.
   *
   * If no host is present a null will be returned.
   *
   * If host is present, an associated array will be returned.
   *
   * @return array|null
   */
  public function getAuthority(): ?array;

  /**
   * Retrieve the URI authority string
   *
   * Return empty string if no authority is present, otherwise a string will
   * be returned if such format `[user-info@]host[:port]`.
   *
   * If no host is present, an empty string will be returned.
   *
   * If the port is not set or is the port standard for the current scheme,
   * the port will not be included.
   *
   * @return string
   */
  public function getAuthorityString(): string;
  
  /**
   * Retrieve the URI user information as an associated array string.
   *
   * Returns null if no user information, else will return an array for the 
   * presence of user, also if password is present will be an additional.
   *
   * @return array|null
   */
  public function getUserInfo(): ?array;
  
  /**
   * Retrieve the URI user information in a string format
   *
   * This is the same as getUserInfo(), but this return the user information 
   * in string format of `user[:password]`
   *
   * Return empty if no user information, else it will return user if present
   * also if password is present the value will be return with password
   *
   * @return string 
   */
  public function getUserInfoString(): string;
  
  /**
   * Retrieve the URI host
   *
   * This method return an empty string if no host in the URI, otherwise host
   * name will be returned as string.
   *
   * @return string
   */
  public function getHost(): string;
  
  /**
   * Retrieve the URI port number
   *
   * Return the port if present if and only if the current scheme is present,
   * otherwise will return null.
   *
   * If the current scheme is present and port is not present a null will be 
   * returned
   *
   * @return int|null
   */
  public function getPort(): ?int;
  
  /**
   * Retrieve the URI path
   *
   * The method return an empty string, an absolute or rootless path.
   *
   * @return string
   */
  public function getPath(): string;
  
  /**
   * Retrieve the URI query key-value pair
   *
   * Returns null if no query is present. If query is present an associated
   * array will be returned.
   *
   * @return array|null
   */
  public function getQuery(): ?array;
  
  /**
   * Retrieve the URI query string
   *
   * Returns empty string if no query is present. The value returned is 
   * URL-encoded string
   *
   * @return string
   */
  public function getQueryString(): string;
  
  /**
   * Retrieve the URI fragment
   *
   * Return empty string if no fragment is present. The value returned is
   * URL-encoded string
   *
   * @return string
   */
  public function getFragment(): string;
  
  /**
   * Retrieve the string representation of the URI
   *
   * The returned value is URL-encoded string.
   *
   * @return string
   */
  public function __toString(): string;
}
