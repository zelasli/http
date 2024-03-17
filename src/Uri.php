<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http;

use Zelasli\Http\Exceptions\SyntaxError;

/**
 * Implementation of URI interface
 * 
 * @link https://datatracker.ietf.org/doc/html/rfc3986
 */
class Uri implements UriInterface {
  /**
   * HTTP URI Scheme
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.1
   */
  protected string $scheme = '';

  /**
   * HTTP URI user information authority
   *
   * @var array|null
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.1
   */
  protected ?array $userInfo = null;

  /**
   * HTTP URI host authority
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.2
   */
  protected string $host = '';
  
  /**
   * HTTP URI port authority
   *
   * @var int|null
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.3
   */
  protected ?int $port = null;

  /**
   * HTTP URI path
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.3
   */
  protected string $path = '';

  /**
   * HTTP URI query
   *
   * @var array|null
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.4
   */
  protected ?array $query = null;

  /**
   * HTTP URI fragment
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.5
   */
  protected string $fragment = '';

  /**
   * Uri class contructor
   * 
   * @param string $uri
   * 
   * @throws SyntaxError
   */
  public function __construct(string $uri)
  {
      $components = UriFactory::parse($uri);

      if (!empty($components['scheme']) && preg_match(
        '#^' . UriFactory::COMPONENT_SCHEME_REGEX . '*$#', $components['scheme']
      ) !== 1) {
          throw new SyntaxError(
            sprintf("The `%s` scheme is not valid in URI `%s`.", $components['scheme'], $uri)
          );
      }

      $this->scheme = $components['scheme'];
      $this->userInfo = $components['authority']['userinfo'] ?? null;
      $this->host = $components['authority']['host'] ?? '';
      $this->port = $components['authority']['port'] ?? null;
      $this->path = $components['path'];
      $this->query = $components['query'];
      $this->fragment = $components['fragment'];
  }

  public function getScheme(): string
  {
    return $this->scheme;
  }

  public function getAuthority(): ?array
  {
    $authority = null;

    if (!empty($this->host)) {
      $authority['userinfo'] = $this->getUserInfo();
      $authority['host'] = $this->host;
      $authority['port'] = $this->port;
    }

    return $authority;
  }

  public function getAuthorityString(): string
  {
    $authority = '';

    if (!empty($this->host)) {
      $userInfo = $this->getUserInfoString();

      $authority .= !empty($userInfo) ? $userInfo . '@' : '';
      $authority .= $this->host;
      $authority .= !is_null($this->port) ? ':' . $this->port : '';
    }

    return $authority;
  }

  public function getUserInfo(): ?array
  {
    return $this->userInfo;
  }

  public function getUserInfoString(): string
  {
    $userInfo = '';

    if (!empty($this->userInfo['user'])) {
      $userInfo .= $this->userInfo['user'];
      $userInfo .= !empty($this->userInfo['password']) ? 
        ':' . $this->userInfo['password'] : '';
    }

    return $userInfo;
  }

  public function getHost(): string
  {
    return $this->host;
  }

  public function getPort(): ?int
  {
    return $this->port;
  }

  public function getPath(): string
  {
    return $this->path;
  }

  public function getQuery(): ?array
  {
    return $this->query;
  }

  public function getQueryString(): string
  {
    $queryString = '';

    if (!is_null($this->query)) {
      $queryString = http_build_query($this->query, "", null, PHP_QUERY_RFC3986);
    }

    return $queryString;
  }

  public function getFragment(): string
  {
    return $this->fragment;
  }

  public function __toString(): string
  {
    $uriString = '';
    $scheme = $this->getScheme();
    $authority = $this->getAuthorityString();
    $path = $this->getPath();
    $queryString = $this->getQueryString();
    $fragment = $this->getFragment();
    
    $hierPart = '';
    if (!empty($authority)) {
      $path = !empty($path) && !str_starts_with($path, '/') ? '/' . $path : $path;
      $hierPart = "//" . $authority . $path;
      $uriString = $scheme . ':' . $hierPart;
    } else {
      $uriString = $path;
    }

    // Optional components
    $uriString .= !empty($queryString) ? '?' . $queryString : '';
    $uriString .= !empty($fragment) ? '#' . $fragment : '';

    return $uriString;
  }
}
