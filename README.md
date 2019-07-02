# ClientSupport #
ClientSupport is a MODX extra to allow manager users to ask for help or support from inside the MODX manager.
A button is added to the top right section in your MODX manager, which triggers a popup containing a help form where the user can describe their problem/question.
In addition to the form fields the **username**, **IP address**, **manager page URL** and **browser version** is sent along.

ClientSupport provides built-in branding for Zendesk, Freshdesk and Jira. Be sure to have the email-to-ticket feature enabled in your ticket system and 
set the correct system settings for the ticket system and emailaddress in MODX. When one of the mentioned systems is used the button and form are branded accordingly.

The submitted form is sent to a configurable emailaddress where all the submitted data is shown in the email body.

## Enabling email-to-ticket in your ticket system ##
Zendesk: https://support.zendesk.com/hc/en-us/articles/203663256-Getting-started-with-email-in-Zendesk-Support

Freshdesk: https://support.freshdesk.com/support/solutions/articles/37541-forward-and-convert-your-support-emails-to-tickets-in-freshdesk

Jira: https://confluence.atlassian.com/adminjiraserver071/creating-issues-and-comments-from-email-802592933.html

## MODX System settings ##
_email_to_: The emailaddress where the support form is sent to. Defaults to system setting "emailsender".

_email_from_: The emailaddress where the support email is sent from. Defaults to system setting "emailsender".

_email_from_name_: The From-name of the email-sender where the support form is sent to. Defaults to name supplied in the form.

_email_tpl_: The chunk that is used for the support email.

_ticket_system_: The ticket system that is used for branding.

# Free Extra
This is a free extra and the code is publicly available for you to change. The extra is being actively maintained and you're free to put in pull requests which match our roadmap. Please create an issue if the pull request differs from the roadmap so we can make sure we're on the same page.

Need help? [Approach our support desk for paid premium support.](mailto:service@sterc.com)
