# -*- coding: utf-8 -*-
# Generated by Django 1.10.2 on 2017-03-29 08:05
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('squealy', 'default_superuser'),
    ]

    operations = [
        migrations.AddField(
            model_name='scheduledreport',
            name='template',
            field=models.TextField(blank=True, null=True),
        ),
    ]
