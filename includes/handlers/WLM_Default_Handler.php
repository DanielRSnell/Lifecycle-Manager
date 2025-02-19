<?php

class WLM_Default_Handler
{
    public function canHandle($data)
    {
        // This is the default handler, so it can handle any request
        return true;
    }

    public function handle($data)
    {
        // This method should implement the default handling logic
        // For now, we'll just return null
        return null;
    }

    public function canHandleUpdate($data)
    {
        // This is the default handler, so it can handle any update request
        return true;
    }

    public function handleUpdate($data)
    {
        // This method should implement the default update logic
        // For now, we'll just return null
        return null;
    }
}
