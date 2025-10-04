<?php

declare(strict_types=1);

namespace Gbtux\InertiaBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Process\Process;

class InertiaInstallCommand extends Command
{
    /** @var Filesystem $filesystem */
    private $filesystem;
    private $basePath;

    public function __construct(Filesystem $filesystem, string $basePath)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->basePath = $basePath;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('inertia:install')
            ->setDescription('Install the Inertia resources')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Config files
        $this->replaceInFile(
            "/* react()",
            "react()",
            Path::makeAbsolute('vite.config.js', $this->basePath),
        );
        $this->replaceInFile(
          "// import react",
          "import react",
           Path::makeAbsolute('vite.config.js', $this->basePath),
        );
        $this->replaceInFile(
            'app.js"',
            'app.jsx"',
            Path::makeAbsolute('vite.config.js', $this->basePath),
        );

        $this->updateNodePackages(function ($packages) {
            return [
                "@vitejs/plugin-react" => "",
                "@types/react" => "",
                "@types/react-dom" => "",
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
                '@toyokumo/fos-router'=> ''
            ];
        }, false);

        // Templates
        if ($this->filesystem->exists(Path::makeAbsolute('templates/base.html.twig', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('templates/base.html.twig', $this->basePath));
        }
        /*
        $this->filesystem->copy(
            __DIR__.'/../../stubs/templates/app.html.twig',
            Path::makeAbsolute('templates/app.html.twig', $this->basePath)
        );

        // Components + Pages + Styles...
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/js', $this->basePath));
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/js/components', $this->basePath));
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/js/pages', $this->basePath));
        $this->ensureDirectoryExists(Path::makeAbsolute('assets/styles', $this->basePath));

        if ($this->filesystem->exists(Path::makeAbsolute('assets/app.js', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/app.js', $this->basePath));
        }
        if ($this->filesystem->exists(Path::makeAbsolute('assets/bootstrap.js', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/bootstrap.js', $this->basePath));
        }
        if ($this->filesystem->exists(Path::makeAbsolute('assets/controllers.json', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/controllers.json', $this->basePath));
        }
        if ($this->filesystem->exists(Path::makeAbsolute('assets/controllers', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('assets/controllers', $this->basePath));
        }

        $this->filesystem->mirror(
            __DIR__.'/../../stubs/assets/js/components',
            Path::makeAbsolute('assets/js/components', $this->basePath)
        );
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/assets/js/pages',
            Path::makeAbsolute('assets/js/pages', $this->basePath)
        );
        $this->filesystem->remove(Path::makeAbsolute('assets/app.js', $this->basePath));
        /**
        $this->filesystem->copy(
            __DIR__.'/../../stubs/assets/app.jsx',
            Path::makeAbsolute('assets/app.jsx', $this->basePath)
        );
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/assets/styles',
            Path::makeAbsolute('assets/styles', $this->basePath),
            null,
            ['override' => true]
        );*/

        // NPM Packages...
        /**
        $io->info('Installing Node dependencies.');
        if ($this->filesystem->exists(Path::makeAbsolute('pnpm-lock.yaml', $this->basePath))) {
            $this->runCommand('pnpm install', $output);
        } elseif ($this->filesystem->exists(Path::makeAbsolute('yarn.lock', $this->basePath))) {
            $this->runCommand('yarn install', $output);
        } else {
            if ($this->filesystem->exists(Path::makeAbsolute('.nvmrc', $this->basePath))) {
                if($nvmBinary) {
                    $this->runCommand(sprintf('% use', $nvmBinary), $output);
                }else{
                    $this->runCommand('nvm use', $output);
                }

            }
            //$this->runCommand('npm install', $output);
            //$this->runCommand('bin/console fos:js-routing:dump --format=js --target=assets/js/fos_routes.js --callback="export default  "', $output);
        }**/

        /**
        // Controllers...
        $this->ensureDirectoryExists(Path::makeAbsolute('src/Controller', $this->basePath));
        $this->filesystem->mirror(
            __DIR__.'/../../stubs/src/Controller',
            Path::makeAbsolute('src/Controller', $this->basePath)
        );
        if ($this->filesystem->exists(Path::makeAbsolute('src/Controller/.gitignore', $this->basePath))) {
            $this->filesystem->remove(Path::makeAbsolute('src/Controller/.gitignore', $this->basePath));
        }*/

        $io->info('Inertia scaffolding installed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Replace a given string within a given file.
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        $this->filesystem->dumpFile($path, str_replace($search, $replace, file_get_contents($path)));
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

    /**
     * Run the given command.
     */
    protected function runCommand(string $command, OutputInterface $output): void
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $line) use ($output) {
            $output->write('    '.$line);
        });
    }

    protected function ensureDirectoryExists(string $path, int $mode = 0755): void
    {
        if (!$this->filesystem->exists($path)) {
            $this->filesystem->mkdir($path, $mode);
        }
    }
}
