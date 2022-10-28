<?php

declare(strict_types=1);

namespace App\Command\Flat;

use App\Command\AbstractCommand;
use App\Entity\Flat\Cell;
use App\Entity\Flat\World;
use App\Repository\Flat\CellRepository;
use App\Repository\Flat\WorldRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateNewWorldCommand extends AbstractCommand
{
    /**
     * @var string name argument const
     */
    private const ARGUMENT_NAME = 'name';

    /**
     * @var string width argument const
     */
    private const ARGUMENT_WIDTH = 'width';

    /**
     * @var string height argument const
     */
    private const ARGUMENT_HEIGHT = 'height';

    /**
     * @var string default bar size
     */
    private const BAR_WIDTH = 120;

    /**
     * @var string default bar format
     */
    private const BAR_FORMAT = "%bar% [%percent%%]\t%message%\n";

    /**
     * @var WorldRepository
     */
    private WorldRepository $worldRepository;

    /**
     * @var CellRepository
     */
    private CellRepository $cellRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param WorldRepository        $worldRepository
     * @param CellRepository         $cellRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        WorldRepository $worldRepository,
        CellRepository $cellRepository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->worldRepository = $worldRepository;
        $this->cellRepository = $cellRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Create new empty world.')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::REQUIRED, self::ARGUMENT_NAME)
            ->addArgument(self::ARGUMENT_WIDTH, InputArgument::REQUIRED, self::ARGUMENT_WIDTH)
            ->addArgument(self::ARGUMENT_HEIGHT, InputArgument::REQUIRED, self::ARGUMENT_HEIGHT);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = (string) $input->getArgument(self::ARGUMENT_NAME);
        $width = (int) $input->getArgument(self::ARGUMENT_WIDTH);
        $height = (int) $input->getArgument(self::ARGUMENT_HEIGHT);

        $io = new SymfonyStyle($input, $output);

        $io->title('Start creating new world...');
        $io->listing([
            "Name: $name",
            "Width: $width",
            "Height: $height",
        ]);

        $world = $this->createWorld($io, $name, $width, $height);
        $cells = $this->createCells($io, $world);
        $this->saveEntities($io);

        $io->writeln('');

        $io->success('Worlds create success!');
        $io->note('Created cells: ' . \count($cells));

        return self::SUCCESS;
    }

    /**
     * @param SymfonyStyle $io
     * @param string       $name
     * @param int          $width
     * @param int          $height
     *
     * @return World
     */
    private function createWorld(SymfonyStyle $io, string $name, int $width, int $height): World
    {
        $progressBar = $this->createProgressBar($io, 2, 'Create world');

        $world = new World();
        $world->setName($name);
        $world->setWidth($width);
        $world->setHeight($height);
        $world->setTurn(0);

        $progressBar->advance();

        $this->worldRepository->save($world, true);

        $progressBar->advance();
        $progressBar->finish();

        return $world;
    }

    /**
     * @param SymfonyStyle $io
     * @param World        $world
     *
     * @return array<Cell>
     */
    private function createCells(SymfonyStyle $io, World $world): array
    {
        $progressBar = $this->createProgressBar($io, $world->getWidth() * $world->getHeight(), 'Create cells');

        $cells = [];
        for ($x = 0; $x < $world->getWidth(); ++$x) {
            for ($y = 0; $y < $world->getHeight(); ++$y) {
                $cell = new Cell();
                $cell->setX($x);
                $cell->setY($y);
                $cell->setWorld($world);

                $this->cellRepository->save($cell);
                $cells[] = $cell;
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        return $cells;
    }

    /**
     * @param SymfonyStyle $io
     *
     * @return void
     */
    private function saveEntities(SymfonyStyle $io): void
    {
        $progressBar = $this->createProgressBar($io, 2, 'Save in database');

        $progressBar->advance();

        $this->entityManager->flush();

        $progressBar->advance();
        $progressBar->finish();
    }

    /**
     * @param SymfonyStyle $io
     * @param int          $max
     * @param string       $message
     *
     * @return ProgressBar
     */
    private function createProgressBar(SymfonyStyle $io, int $max, string $message): ProgressBar
    {
        $progressBar = $io->createProgressBar($max);

        $progressBar->setBarWidth(self::BAR_WIDTH);
        $progressBar->setFormat(self::BAR_FORMAT);
        $progressBar->setRedrawFrequency(1);
        $progressBar->setMessage($message);

        return $progressBar;
    }
}
