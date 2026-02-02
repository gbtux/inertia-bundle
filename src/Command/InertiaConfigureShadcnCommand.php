<?php

namespace Gbtux\InertiaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Process\Process;

class InertiaConfigureShadcnCommand extends Command
{
    public function __construct(private Filesystem $filesystem, private string $basePath)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('inertia:configure-shadcn')
            ->setDescription('Configure ShadCN for Inertia')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->filesystem->mirror(
            __DIR__.'/../../stubs/shadcn/styles',
            Path::makeAbsolute('assets/styles', $this->basePath),
            null,
            ['override' => true]
        );
        $this->filesystem->copy(
            __DIR__.'/../../stubs/shadcn/vite.config.js',
            Path::makeAbsolute('vite.config.js', $this->basePath)
        );
        $this->filesystem->copy(
            __DIR__.'/../../stubs/shadcn/tsconfig.json',
            Path::makeAbsolute('tsconfig.json', $this->basePath)
        );
        $this->filesystem->copy(
            __DIR__.'/../../stubs/shadcn/components.json',
            Path::makeAbsolute('components.json', $this->basePath)
        );

        $this->updateNodePackages(function ($packages) {
            return [
                    "@vitejs/plugin-react" => "",
                    "@types/react" => "",
                    "@types/react-dom" => "",
                    "@types/node" => "",
                ] + array_filter(
                    $packages,
                    fn ($package) => in_array($package, ['vite', 'vite-plugin-symfony']),
                    ARRAY_FILTER_USE_KEY
                );
        });
        $this->updateNodePackages(function ($packages) {
            return [
                'react' => '',
                'react-dom' => '',
                '@inertiajs/react' => '',
                '@toyokumo/fos-router'=> '',
                'tailwindcss'=> '',
                '@tailwindcss/vite'=> '',
                'tailwindcss-animate'=> '',
                'lucide-react' => ''
            ];
        }, false);
        $io->info('Installing Node dependencies.');
        if ($this->filesystem->exists(Path::makeAbsolute('pnpm-lock.yaml', $this->basePath))) {
            $this->runCommand('pnpm install', $output);
        } elseif ($this->filesystem->exists(Path::makeAbsolute('yarn.lock', $this->basePath))) {
            $this->runCommand('yarn install', $output);
        } else {
            $this->runCommand('npm install', $output);
        }
        $io->info('Init Shadcn....');
        $this->runCommand('npx shadcn@latest init', $output);

        return Command::SUCCESS;
    }


    /**
     * Update the "package.json" file.
     */
    protected function updateNodePackages(callable $callback, bool $dev = true): void
    {
        $packageJsonFile = Path::makeAbsolute('package.json', $this->basePath);

        if (!$this->filesystem->exists($packageJsonFile)) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents($packageJsonFile), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        $this->filesystem->dumpFile(
            $packageJsonFile,
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected function runCommand(string $command, OutputInterface $output): void
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $line) use ($output) {
            $output->write('    '.$line);
        });
    }

}