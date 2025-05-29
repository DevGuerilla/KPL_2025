<?php

class SecurityException extends Exception
{
    private array $context;
    private string $securityLevel;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $securityLevel = 'HIGH'
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->securityLevel = $securityLevel;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getSecurityLevel(): string
    {
        return $this->securityLevel;
    }

    public function logSecurityEvent(): void
    {
        Logger::security('Security Exception: ' . $this->getMessage(), [
            'exception_class' => get_class($this),
            'security_level' => $this->securityLevel,
            'context' => $this->context,
            'trace' => $this->getTraceAsString()
        ]);
    }
}