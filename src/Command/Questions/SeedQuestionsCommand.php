<?php

namespace App\Command\Questions;

use App\Contract\PhoneNumber\Validator\PhoneNumber;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

#[AsCommand(
    name: 'app:seed-questions',
    description: 'Add a short description for your command',
)]
class SeedQuestionsCommand extends Command
{
    protected static $defaultName = 'app:seed-questions';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuestionRepository $questionRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Seed the questions table with default values');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /* @var QuestionDto[] $questions */
        $questions = [
            new QuestionDto(
                'first_name',
                'string',
                [new Assert\Length(['min' => 2, 'max' => 100])],
                true,
                dependsOn: ['last_name']
            ),
            new QuestionDto(
                'last_name',
                'string',
                [new Assert\Length(['min' => 2, 'max' => 100])],
                true,
                dependsOn: ['first_name']
            ),
            new QuestionDto(
                'email',
                'string',
                [new Assert\Email()],
                true
            ),
            new QuestionDto(
                'phone',
                PhoneNumber::class,
                [new PhoneNumber(['defaultRegion' => 'PL', 'type' => PhoneNumber::MOBILE])],
            ),
            new QuestionDto(
                'age',
                'integer',
                [new Assert\Range(['min' => 0, 'max' => 100])],
                true
            ),
            new QuestionDto(
                'pesel',
                'string',
                [new Assert\Length(['value' => 11])],
                true
            ),
            new QuestionDto(
                'city',
                'string',
                [new Assert\Length(['min' => 2, 'max' => 100])],
            ),
            new QuestionDto(
                'street',
                'string',
                [new Assert\Length(['min' => 1, 'max' => 100]), new Assert\IsNull()],
            ),
            new QuestionDto(
                'house_no',
                'string',
                [new Assert\Length(['min' => 1, 'max' => 6])],
                dependsOn: ['street', 'city']
            ),
            new QuestionDto(
                'postal_code',
                'string',
                [new Assert\Regex('/^\d{2}-\d{3}$/')],
                dependsOn: ['city']
            ),
        ];

        foreach ($questions as $data) {
            $question = $this->questionRepository->findOneBy(['questionKey' => $data->getQuestionKey()]) ?: new Question();
            $data->fillQuestion($question);
            $this->entityManager->persist($question);
        }

        $this->entityManager->flush();

        $io->success('Questions table seeded successfully.');

        return Command::SUCCESS;
    }
}
