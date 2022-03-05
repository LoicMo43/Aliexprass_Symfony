<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('user.FullName', 'Client'),
            TextField::new('carrierName', 'Carrier Name'),
            MoneyField::new('carrierPrice', 'Shipping')->setCurrency('USD'),
            MoneyField::new('subTotalHT', 'Subtotal HT')->setCurrency('USD'),
            MoneyField::new('taxe', 'TVA')->setCurrency('USD'),
            MoneyField::new('subTotalTTC', 'Subtotal TTC')->setCurrency('USD'),
            BooleanField::new('isPaid')
        ];
    }
}
