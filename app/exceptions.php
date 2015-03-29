<?php

namespace Slovotepec;


class MemberAccessException extends \LogicException
{
}

class InvalidStateException extends \RuntimeException
{
}

class InvalidArgumentException extends \InvalidArgumentException
{
}

class NotImplementedException extends \LogicException
{
}

class NotSupportedException extends \LogicException
{
}

class DeprecatedException extends NotSupportedException
{
}

class IOException extends \RuntimeException
{
}

class FileNotFoundException extends IOException
{
}

class DirectoryNotFoundException extends IOException
{
}
