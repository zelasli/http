<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http\Exceptions;

use InvalidArgumentException;

class SyntaxError extends InvalidArgumentException implements UriException {}
