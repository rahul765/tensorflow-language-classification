# -*- coding: utf-8 -*-

from django import template
from django.conf import settings
from django.forms import widgets
from django.template import Context
from django.template.loader import get_template
from django.utils.safestring import mark_safe

register = template.Library()


@register.simple_tag
def nungoforms(field, template=None):
    """
    Utilização:
    {% nungoforms form.name 'template/path.html' %}
    """

    if not template:

        template = '_nungoforms/bootstrap-v3/input.html'
        
        if type(field.field.widget) in [widgets.CheckboxInput]:

            template = '_nungoforms/bootstrap-v3/checkbox.html'

    context = {
        'field': field,
        'form': field.form,
    }

    return mark_safe(get_template(template).render(context))