<?php

namespace App\Console\Commands;

use App\Models\BlockchainToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class UpdateTokensList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-tokens-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tokens list from CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating tokens list...');

        $csv = Reader::createFromPath(storage_path('data/tokens.csv'), 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        DB::transaction(function () use ($records) {
            foreach ($records as $record) {
                BlockchainToken::updateOrCreate(
                    ['contract_address' => $record['contract_address']],
                    [
                        'name' => $record['name'],
                        'symbol' => $record['symbol'],
                        'network' => $record['network'],
                    ]
                );
            }
        });

        $this->info('Tokens list updated successfully');
    }
}
