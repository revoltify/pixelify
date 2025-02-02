<?php

namespace Revoltify\Pixelify\Commands;

use Illuminate\Console\Command;

class PixelifySetupCommand extends Command
{
    protected $signature = 'pixelify:setup';

    protected $description = 'Setup Facebook Pixel configuration';

    public function handle(): int
    {
        $this->info('Setting up Pixelify...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'pixelify-config',
        ]);

        $this->info('✅ Configuration file published successfully.');

        // Remind about environment variables
        $this->info('Please add the following variables to your .env file:');
        $this->line('FACEBOOK_PIXEL_ID=your_pixel_id');
        $this->line('FACEBOOK_CONVERSION_API_TOKEN=your_api_token');
        $this->line('FACEBOOK_TEST_EVENT_CODE=your_test_event_code (optional)');
        $this->line('FACEBOOK_PIXEL_DEBUG=false');

        return self::SUCCESS;
    }
}
