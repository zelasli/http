<?php

/**
 * Zelasli HTTP
 *
 * @author Rufai Limantawa <rufailimantawa@gmail.com>
 * @package Zelasli\Http
 */

namespace Zelasli\Http\Message;

/**
 * Data stream
 */
interface StreamInterface {
  /**
   * Get the size of the stream.
   *
   * @return int|null
   */
  public function getSize(): ?int;

  /**
   * Get the current position of the stream read/write pointer.
   *
   * @return int
   */
  public function tell(): int;

  /**
   * Returns true if the stream is at the end of stream.
   *
   * @return bool
   */
  public function eof(): bool;

  /**
   * Returns whether or not the stream is seekable.
   * 
   * @return bool
   */
  public function isSeekable(): bool;

  /**
   * Seek to a position in the stream.
   *
   * @param int $offset
   * @param int $whence
   *
   * @throws \RuntimeException on failure
   */
  public function seek(int $offset, int $whence = SEEK_SET): void;

  /**
   * Seek to the beginning of the stream.
   *
   * If the stream is not seekable an exception will be thrown.
   *
   * @throws \RuntimeException on failure
   */
  public function rewind(): void;

  /**
   * Returns whether or not the stream is writable
   *
   * @return bool
   */
  public function isWritable(): bool;

  /**
   * Write data to the stream.
   *
   * @param string $data
   *
   * @return int the number of bytes written to the stream
   * @throws \RuntimeException on failure
   */
  public function write(string $data): int;

  /**
   * Returns whether or not the stream is readable
   *
   * @return bool
   */
  public function isReadable(): bool;

  /**
   * Read data from the stream.
   *
   * @param int $length
   *
   * @return string
   * @throws \RuntimeException on failure
   */
  public function read(int $length): string;

  /**
   * Read line from the stream pointer position.
   *
   * @param int $length
   *
   * @return string
   * @throws \RuntimeException on failure
   */
  public function readLine(): string;

  /**
   * Gets character from the stream pointer
   *
   * @return string
   * @throws \RuntimeException on failure
   */
  public function getChar(): string;

  /**
   * Reads entire contents of the stream.
   *
   * @return string
   * @throws \RuntimeException on failure
   */
  public function getContents(): string;

  /**
   * Closes the stream resources
   *
   * @return void
   */
  public function close(): void;
}
