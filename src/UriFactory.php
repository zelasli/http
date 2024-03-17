<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http;

use Stringable;
use Zelasli\Http\Exceptions\SyntaxError;

/**
 * URI Factory class for working with URI implementation of RFC 3986
 *
 * @link https://datatracker.ietf.org/doc/html/rfc3986
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

class UriFactory {
  /**
   * Invalid characters
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc2396#section-2.4.3
   */
  const CHAR_INVALID = '[\x00-\x1f\x7f]';

  /**
   * URI percent-encoding character
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-2.1
   */
  const CHAR_PERCENT_ENCODING = '%';

  /**
   * URI Generic components delimiters
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-2.2
   */
  const CHAR_GENERIC_DELIMS = ':\/\?#\[\]@';
  
  /** 
   * URI Subcomponents delimiters
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-2.2
   */
  const CHAR_SUBCOMPONENTS_DELIMS = '!\$&\'\(\)\*\+,;=';
  
  /**
   * URI reserved delimiters
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-2.2
   */
  const CHAR_RESERVED = self::CHAR_GENERIC_DELIMS . 
    self::CHAR_SUBCOMPONENTS_DELIMS;

  /**
   * URI unreserved delimeters
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-2.3
   */
  const CHAR_UNRESERVED = 'a-zA-Z0-9\-\._~';

  /**
   * URI regular expressions reference
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#appendix-B
   */
  const URI_REGEX = '|^
  # scheme component
  (
      (?<scheme>[^:/?#]+):
  )?
  # authority component
  (//
      (?<authority>[^/?#]*)
  )?
  # path component
  (?<path>[^?#]*)
  # query component
  (\?
      (?<query>[^#]*)
  )?
  # fragment component
  (\#
      (?<fragment>.*)
  )?
  |x';

  /**
   * URI scheme component regex
   * 
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.1
   */
  const COMPONENT_SCHEME_REGEX = '[a-zA-Z][a-zA-Z0-9+-.]';

  /**
   * URI authority component regex
   * 
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2
   */
  const COMPONENT_AUTHORITY_REGEX = '#^
  ((?<userinfo>[' . self::COMPONENT_USERINFO_REGEX . ']*)@)?
  (?<host>(' . self::COMPONENT_HOST_REGEX .'))?
  (:(?<port>[\d]{1,5}))?
  #x';

  /**
   * URI User information component regex
   * 
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.1
   */
  const COMPONENT_USERINFO_REGEX = self::CHAR_UNRESERVED . 
  self::CHAR_PERCENT_ENCODING . self::CHAR_SUBCOMPONENTS_DELIMS . ':';

  /**
   * IP-literal regex
   *
   * @var string
   */
  const IP_LITERAL = '\['. self::IP_V6_ADDRESS .'\]|'. self::IP_V4_ADDRESS;

  /**
   * 16 bits of address represented in hexadecimal
   *
   * @var string
   */
  const IP_SEG_HEX16 = '[0-9a-fA-F]{1,4}';

  /**
   * IP v6 regex
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.2
   * @see https://gist.github.com/syzdek/6086792
   */
  const IP_V6_ADDRESS = '(
      ('. self::IP_SEG_HEX16 .':){7,7}'. self::IP_SEG_HEX16 .'|
      ('. self::IP_SEG_HEX16 .':){1,7}:|
      ('. self::IP_SEG_HEX16 .':){1,6}:'. self::IP_SEG_HEX16 .'|
      ('. self::IP_SEG_HEX16 .':){1,5}(:'. self::IP_SEG_HEX16 .'){1,2}|
      ('. self::IP_SEG_HEX16 .':){1,4}(:'. self::IP_SEG_HEX16 .'){1,3}|
      ('. self::IP_SEG_HEX16 .':){1,3}(:'. self::IP_SEG_HEX16 .'){1,4}|
      ('. self::IP_SEG_HEX16 .':){1,2}(:'. self::IP_SEG_HEX16 .'){1,5}|
      '. self::IP_SEG_HEX16 .':((:'. self::IP_SEG_HEX16 .'){1,6})|
      :((:'. self::IP_SEG_HEX16 .'){1,7}|:)|
      fe80:(:'. self::IP_SEG_HEX16 .'){0,4}%[0-9a-zA-Z]{1,}|
      ::(ffff(:0{1,4}){0,1}:){0,1}'. self::IP_V4_ADDRESS .'|
      ('. self::IP_SEG_HEX16 .':){1,4}:'. self::IP_V4_ADDRESS .'
  )';

  /**
   * IP v4 regex
   *
   * @var string
   */
  const IP_V4_ADDRESS = '(
  (([0-9]|[1-9][0-9]|1[0-9]{2,2}|2[0-4][0-9]|25[0-5])\.)
  {3,3}
  ([0-9]|[1-9][0-9]|1[0-9]{2,2}|2[0-4][0-9]|25[0-5]))';

  /**
   * URI host component regex
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.2
   */
  const COMPONENT_HOST_REGEX = '' . self::IP_LITERAL .'|['.
  self::CHAR_UNRESERVED . self::CHAR_PERCENT_ENCODING .
  self::CHAR_SUBCOMPONENTS_DELIMS . ']*';

  /**
   * URI port component regex
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.2.3
   */
  const COMPONENT_PORT_MAX = 0xFFFF;

  /**
   * URI path component
   *
   * @var string
   * @link https://datatracker.ietf.org/doc/html/rfc3986#section-3.3
   */
  const COMPONENT_PATH_REGEX = '[^\?#]';

  /**
   * The default URI ports number
   *
   * @var <string, int>
   */
  const STANDARD_PORTS =
  [
    'ftp'    => 21,
    'telnet' => 23,
    'tn3270' => 23,
    'http'   => 80, 
    'gopher' => 70,
    'pop'    => 110,
    'nntp'   => 119,
    'news'   => 119,
    'imap'   => 143,
    'ldap'   => 389,
    'https'  => 443,
  ];

  /**
   * Parse a URI Reference with a Regular Expression
   *
   * Extract URI components from stringable or string literal. The regex is 
   * according to appendix B of RFC3986
   *
   * <code>
   * $uri = UriFacotry::parse('http://www.ics.uci.edu/pub/ietf/uri/#Related');
   * var_export($uri);
   * // Output
   * array (
   * 'scheme' => 'http',
   * 'authority' => 'www.ics.uci.edu',
   * 'path' => '/pub/ietf/uri/',
   * 'query' => '',
   * 'fragment' => 'Related',
   * )
   * </code>
   *
   * @param string|Stringable $uri
   *
   * @link https://datatracker.ietf.org/doc/html/rfc3986#appendix-B
   * @link https://datatracker.ietf.org/doc/html/rfc2396#section-2.4.3
   *
   * @return <string, string>
   * @throws SyntaxError if $uri contains control characters
   */
  public static function parse(string|Stringable $uri)
  {
    // Type cast the Stringable object to string
    $uri = (string) $uri;
  
    // Throw SyntaxError if $uri contains invalid characters 
    // (control characters) from 0 to 31 and 127
    if (preg_match(self::CHAR_INVALID, $uri) === 1) {
      throw new SyntaxError(
        sprintf("The URI `%s` contains an invalid characters.", $uri)
      );
    }

    // Extract the component
    preg_match(self::URI_REGEX, $uri, $components);

    // Filter only the components content
    $components = array_filter(
      $components, 
      fn ($k) => in_array($k, ['scheme', 'authority', 'path', 'query', 'fragment']), 
      ARRAY_FILTER_USE_KEY
    );
    // Add empty components
    $components += [ 'path' => '', 'query' => '', 'fragment' => ''];

    $components['authority'] = !empty($components['authority']) ? 
    self::parseAuthority($components['authority']) : null;
    $components['query'] = !empty($components['query']) ? 
    self::parseQuery($components['query']) : null;

    // Omit default port
    if (isset(self::STANDARD_PORTS[$components['scheme']]) && !empty($components['authority']['port'])) {
      $components['authority']['port'] = 
      ($components['authority']['port'] == self::STANDARD_PORTS[$components['scheme']]) ?
      null : $components['authority']['port'];
    }
    
    return $components;
  }

  /**
   * Parse URI authority subcomponent
   *
   * @param string $authority
   *
   * @return array|null
   * @throws SyntaxError
   */
  protected static function parseAuthority(string $authority): ?array
  {
    $auth = null;

    if (!empty($authority)) {
      preg_match(self::COMPONENT_AUTHORITY_REGEX, $authority, $components);
    }

    // Filter only the components content
    $components = array_filter(
      $components, 
      fn ($k) => in_array($k, ['userinfo', 'host', 'port']), 
      ARRAY_FILTER_USE_KEY
    );
    $components += ['port' => null];
      
    if (!empty($components['userinfo'])) {
      $userinfo = explode(':', $components['userinfo'], 2);

      if (count($userinfo) > 1) {
        $auth['userinfo'] = [
          'user' => $userinfo[0],
          'password' => $userinfo[1]
        ];
      } else {
          $auth['userinfo']['user'] = $userinfo[0];
      }
    }
      
    $auth['host'] = $components['host'];
    if (!empty($port = $components['port'])) {
      if ($port < 0 || $port > 0xFFFF) {
          throw new SyntaxError(
            sprintf("Invalid URI port `%s`", $port)
          );
      }

      $auth['port'] = (int) $port;
    } else {
      $auth['port'] = null;
    }
      
    return $auth;
  }

  /**
   * Parse URI Query string subcomponent
   *
   * @param string $queryString
   *
   * @return array|null
   */
  protected static function parseQuery($queryString): ?array
  {
      $qeury = null;

      if (!empty($queryString)) {
          parse_str($queryString, $qeury);
      }

      return $qeury;
  }
}
