# -*- coding: utf-8 -*-
# Generated by Django 1.10.2 on 2017-04-09 23:34
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('squealy', '0007_auto_20170403_1136'),
    ]

    operations = [
        migrations.AddField(
            model_name='chart',
            name='transpose',
            field=models.BooleanField(default=False),
        ),
    ]
