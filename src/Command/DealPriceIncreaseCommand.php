<?php

namespace App\Command;

use App\Entity\Deal;
use App\Repository\DealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\Table;

#[AsCommand(
    name: 'deal:price:increase',
    description: 'Augmente le prix des deals',
)]
class DealPriceIncreaseCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DealRepository $dealRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('price', InputArgument::REQUIRED, 'Augmentation du prix à appliquer')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'ID du deal à modifier')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Modifier tous les deals')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        // Vérifier que les deux options ne sont pas utilisées en même temps
        if ($input->getOption('id') && $input->getOption('all')) {
            $io->error('Vous ne pouvez pas utiliser les options --id et --all ensemble');
            throw new \RuntimeException('Les options --id et --all ne peuvent pas être utilisées simultanément');
        }

        // Vérifier qu'au moins une option est fournie
        if (!$input->getOption('id') && !$input->getOption('all')) {
            $io->error('Vous devez spécifier soit --id soit --all');
            throw new \RuntimeException('Vous devez spécifier soit --id soit --all');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $priceIncrease = floatval($input->getArgument('price'));

        if ($input->getOption('id')) {
            return $this->updateSingleDeal($input, $output, $io, $priceIncrease);
        }

        if ($input->getOption('all')) {
            return $this->updateAllDeals($output, $io, $priceIncrease);
        }

        return Command::SUCCESS;
    }

    private function updateSingleDeal(InputInterface $input, OutputInterface $output, SymfonyStyle $io, float $priceIncrease): int
    {
        $dealId = intval($input->getOption('id'));
        $deal = $this->dealRepository->find($dealId);

        if (!$deal) {
            $io->error(sprintf('Aucun deal trouvé avec l\'ID %d', $dealId));
            return Command::FAILURE;
        }

        $oldPrice = $deal->getPrice();
        $newPrice = $oldPrice + $priceIncrease;
        
        $deal->setPrice($newPrice);
        $this->entityManager->flush();

        $io->success(sprintf('Deal #%d mis à jour avec succès', $dealId));
        $io->writeln(sprintf('Ancien prix: %.2f €', $oldPrice));
        $io->writeln(sprintf('Nouveau prix: %.2f €', $newPrice));

        return Command::SUCCESS;
    }

    private function updateAllDeals(OutputInterface $output, SymfonyStyle $io, float $priceIncrease): int
    {
        $deals = $this->dealRepository->findAll();

        if (empty($deals)) {
            $io->warning('Aucun deal trouvé dans la base de données');
            return Command::SUCCESS;
        }

        $tableData = [];

        foreach ($deals as $deal) {
            $oldPrice = $deal->getPrice();
            $newPrice = $oldPrice + $priceIncrease;
            
            $deal->setPrice($newPrice);
            
            $tableData[] = [
                $deal->getId(),
                sprintf('%.2f €', $oldPrice),
                sprintf('%.2f €', $newPrice)
            ];
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d deals mis à jour avec succès', count($deals)));
        
        $io->writeln('');
        $io->writeln('Récapitulatif des modifications:');
        
        $table = new Table($output);
        $table
            ->setHeaders(['ID', 'Ancien prix', 'Nouveau prix'])
            ->setRows($tableData);
        $table->render();

        return Command::SUCCESS;
    }
}
