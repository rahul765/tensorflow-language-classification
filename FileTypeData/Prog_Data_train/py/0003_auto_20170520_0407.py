# -*- coding: utf-8 -*-
# Generated by Django 1.11.1 on 2017-05-20 04:07
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('property', '0002_auto_20170519_1647'),
    ]

    operations = [
        migrations.AlterField(
            model_name='location',
            name='popular',
            field=models.CharField(blank=True, max_length=10, null=True),
        ),
        migrations.AlterField(
            model_name='prop',
            name='is_parent',
            field=models.CharField(blank=True, max_length=5, null=True),
        ),
    ]
