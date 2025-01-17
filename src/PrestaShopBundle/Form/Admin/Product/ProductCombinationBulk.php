<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShopBundle\Form\Admin\Product;

use PrestaShop\PrestaShop\Core\Domain\Configuration\ShopConfigurationInterface;
use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use PrestaShopBundle\Form\Admin\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @deprecated since 8.1 and will be removed in next major.
 *
 * This form class is responsible to generate the form for bulk combination feature
 * Note this form is not validated from the server side.
 */
class ProductCombinationBulk extends CommonAbstractType
{
    private $translator;
    private $configuration;

    public function __construct(TranslatorInterface $translator, ShopConfigurationInterface $configuration)
    {
        $this->translator = $translator;
        $this->configuration = $configuration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $is_stock_management = $this->configuration->getBoolean('PS_STOCK_MANAGEMENT');
        $isoCode = $options['iso_code'];

        if ($is_stock_management) {
            $builder->add('quantity', NumberType::class, [
                'required' => true,
                'label' => $this->translator->trans('Quantity', [], 'Admin.Catalog.Feature'),
            ]);
        }

        $builder->add('cost_price', MoneyType::class, [
            'required' => false,
            'label' => $this->translator->trans('Cost Price', [], 'Admin.Catalog.Feature'),
            'attr' => ['data-display-price-precision' => self::PRESTASHOP_DECIMALS],
            'currency' => $isoCode,
        ])
            ->add('impact_on_weight', NumberType::class, [
                'required' => false,
                'label' => $this->translator->trans('Impact on weight', [], 'Admin.Catalog.Feature'),
            ])
            ->add('impact_on_price_te', MoneyType::class, [
                'required' => false,
                'label' => $this->translator->trans('Impact on price (tax excl.)', [], 'Admin.Catalog.Feature'),
                'currency' => $isoCode,
            ])
            ->add('impact_on_price_ti', MoneyType::class, [
                'required' => false,
                'mapped' => false,
                'label' => $this->translator->trans('Impact on price (tax incl.)', [], 'Admin.Catalog.Feature'),
                'currency' => $isoCode,
            ])
            ->add('date_availability', DatePickerType::class, [
                'required' => false,
                'label' => $this->translator->trans('Availability date', [], 'Admin.Catalog.Feature'),
                'attr' => ['class' => 'date', 'placeholder' => 'YYYY-MM-DD'],
            ])
            ->add('reference', TextType::class, [
                'required' => false,
                'label' => $this->translator->trans('Reference', [], 'Admin.Catalog.Feature'),
                'empty_data' => '',
            ])
            ->add('minimal_quantity', NumberType::class, [
                'required' => false,
                'label' => $this->translator->trans('Minimum quantity', [], 'Admin.Catalog.Feature'),
            ])
            ->add('low_stock_threshold', NumberType::class, [
                'required' => false,
                'label' => $this->translator->trans('Low stock level', [], 'Admin.Catalog.Feature'),
            ])
            ->add('low_stock_alert', CheckboxType::class, [
                'required' => false,
                'label' => $this->translator->trans('Send me an email when the quantity is below or equals this level', [], 'Admin.Catalog.Feature'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => false,
            'iso_code' => '',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'product_combination_bulk';
    }
}
