<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Slide;
use App\Service\Admin\CrudService;
use App\Service\Modules\ImageService;
use App\Service\Modules\LangService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use App\Service\Modules\TranslateService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class SlideCrudController extends AbstractCrudController
{
    private string $lang;

    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private ImageService $imageService,
        private readonly CrudService $crudService,
        private readonly LangService $langService,
        private readonly TranslateService $translateService,
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator
    ) {
        $this->lang = $this->langService->getDefault();
        if($this->requestStack->getCurrentRequest()){
            $locale = $this->requestStack->getCurrentRequest()->getSession()->get('_locale');
            if($locale){
                $this->lang = $this->requestStack->getCurrentRequest()->getSession()->get('_locale');
                $this->translateService->setLangs($this->lang);
                $this->langService->setLang($this->lang);
            }
        }
    }
    
    public static function getEntityFqcn(): string
    {
        return Slide::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Slide) return;

        $file = $this->getContext()->getRequest()->files->get('Slide')['image'] ?? null;
        $this->imageService->processImage($file, $entityInstance,"slide");

        $this->crudService->setEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Slide) return;

        /** @var UploadedFile|null $file */
        $file = $this->getContext()->getRequest()->files->get('Slide')['image'] ?? null;
        $this->imageService->processImage($file, $entityInstance,"slide");

        $this->crudService->setEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        /**
         * on forms
         */
        yield FormField::addTab($this->translateService->translateWords("options"));
            yield Field::new('image', $this->translateService->translateWords("image"))
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'required' => false,
                    'mapped' => false,
                ])
                ->onlyOnForms();
            yield BooleanField::new('active',$this->translateService->translateWords("active"))
                ->renderAsSwitch(true)
                ->setFormTypeOptions(['data' => true])
                ->onlyOnForms();
            yield AssociationField::new('target', $this->translateService->translateWords("target"))
                ->setRequired(false)
                ->autocomplete()
                ->hideOnIndex();

        // ✅ Default language tab - use custom getter/setter
        yield FormField::addTab($this->translateService->translateWords($this->langService->getDefaultObject()->getName()));
            yield TextField::new('name', $this->translateService->translateWords("name"))
                ->setFormTypeOption('getter', function(Slide $entity) {
                    return $entity->getName($this->langService->getDefault());
                })
                ->setFormTypeOption('setter', function(Slide &$entity, $value) {
                    $entity->setName($value, $this->langService->getDefault());
                })
                ->hideOnIndex();
            yield TextareaField::new('text', $this->translateService->translateWords("text"))
                ->setFormTypeOption('getter', function(Slide $entity) {
                    return $entity->getText($this->langService->getDefault());
                })
                ->setFormTypeOption('setter', function(Slide &$entity, $value) {
                    $entity->setText($value, $this->langService->getDefault());
                })
                ->hideOnIndex();
            yield TextField::new('title', $this->translateService->translateWords("title"))
                ->setFormTypeOption('getter', function(Slide $entity) {
                    return $entity->getTitle($this->langService->getDefault());
                })
                ->setFormTypeOption('setter', function(Slide &$entity, $value) {
                    $entity->setTitle($value, $this->langService->getDefault());
                })
                ->hideOnIndex();
            yield TextField::new('alt', $this->translateService->translateWords("alt"))
                ->setFormTypeOption('getter', function(Slide $entity) {
                    return $entity->getAlt($this->langService->getDefault());
                })
                ->setFormTypeOption('setter', function(Slide &$entity, $value) {
                    $entity->setAlt($value, $this->langService->getDefault());
                })
                ->hideOnIndex();
            yield TextField::new('slug', $this->translateService->translateWords("url"))
                ->setFormTypeOption('getter', function(Slide $entity) {
                    return $entity->getSlug($this->langService->getDefault());
                })
                ->setFormTypeOption('setter', function(Slide &$entity, $value) {
                    $entity->setSlug($value, $this->langService->getDefault());
                })
                ->hideOnIndex();
        
        // ✅ Other language tabs - use custom getter/setter for each
        foreach($this->langService->getLangs() as $lang){
            if(!$lang->isDefault()){
                $langCode = $lang->getCode();
                
                yield FormField::addTab($this->translateService->translateWords($lang->getName()));
                
                yield TextField::new('name_' . $langCode, $this->translateService->translateWords("name"))
                    ->setFormTypeOption('getter', function(Slide $entity) use ($langCode) {
                        return $entity->getName($langCode);
                    })
                    ->setFormTypeOption('setter', function(Slide &$entity, $value) use ($langCode) {
                        $entity->setName($value, $langCode);
                    })
                    ->hideOnIndex();
                
                yield TextareaField::new('text_' . $langCode, $this->translateService->translateWords("text"))
                    ->setFormTypeOption('getter', function(Slide $entity) use ($langCode) {
                        return $entity->getText($langCode);
                    })
                    ->setFormTypeOption('setter', function(Slide &$entity, $value) use ($langCode) {
                        $entity->setText($value, $langCode);
                    })
                    ->hideOnIndex();
                
                yield TextField::new('title_' . $langCode, $this->translateService->translateWords("title"))
                    ->setFormTypeOption('getter', function(Slide $entity) use ($langCode) {
                        return $entity->getTitle($langCode);
                    })
                    ->setFormTypeOption('setter', function(Slide &$entity, $value) use ($langCode) {
                        $entity->setTitle($value, $langCode);
                    })
                    ->hideOnIndex();
                
                yield TextField::new('alt_' . $langCode, $this->translateService->translateWords("alt"))
                    ->setFormTypeOption('getter', function(Slide $entity) use ($langCode) {
                        return $entity->getAlt($langCode);
                    })
                    ->setFormTypeOption('setter', function(Slide &$entity, $value) use ($langCode) {
                        $entity->setAlt($value, $langCode);
                    })
                    ->hideOnIndex();
                
                yield TextField::new('slug_' . $langCode, $this->translateService->translateWords("url"))
                    ->setFormTypeOption('getter', function(Slide $entity) use ($langCode) {
                        return $entity->getSlug($langCode);
                    })
                    ->setFormTypeOption('setter', function(Slide &$entity, $value) use ($langCode) {
                        $entity->setSlug($value, $langCode);
                    })
                    ->hideOnIndex();
            }
        }
        
        /**
         * index
         */
        yield TextField::new('getIdAsString', $this->translateService->translateWords('identification'))
            ->onlyOnIndex()
            ->formatValue(function ($value, $entity) {
                $idString = $entity->getIdAsString();

                $url = $this->adminUrlGenerator
                    ->setController(self::class)
                    ->setAction('edit')
                    ->setEntityId($idString)
                    ->generateUrl();

                return sprintf('<a href="%s">%s</a>', $url, htmlspecialchars($idString));
            })
            ->renderAsHtml()
            ->onlyOnIndex();
        
        yield TextField::new('name', $this->translateService->translateWords("name"))
            ->formatValue(function ($value, Slide $entity) {
                $default = $this->langService->getDefault();
                $name = $entity->getName($default);
                
                $url = $this->adminUrlGenerator
                    ->setController(self::class)
                    ->setAction('edit')
                    ->setEntityId($entity->getId())
                    ->generateUrl();

                return sprintf('<a href="%s">%s</a>', $url, htmlspecialchars($name));
            })
            ->onlyOnIndex()
            ->renderAsHtml();
        
        yield ImageField::new('image', $this->translateService->translateWords("image"))
            ->setBasePath('/uploads/slide')
            ->formatValue(function ($value, $entity) {
                if (!$value) {
                    return null;
                }

                return "/uploads/slide/{$value}.webp";
            })
            ->addCssClass('index-image')
            ->onlyOnIndex();
        yield DateField::new('created_at', $this->translateService->translateWords("created_at","created"))->hideOnForm();
        yield DateField::new('modified_at',$this->translateService->translateWords("modified_at","modified"))->hideOnForm();
        yield BooleanField::new('active', $this->translateService->translateWords("active"))
            ->renderAsSwitch(true)
            ->onlyOnIndex();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('admin/slide/slide_upload_with_preview.html.twig')
            ->addFormTheme('@EasyAdmin/crud/form_theme.html.twig')
            ->overrideTemplates([
                'crud/index' => 'admin/slide/index.html.twig',
            ]);
    }
}
