import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';
import 'mitologi_top_bar.dart';

class MitologiScaffold extends StatelessWidget {
  const MitologiScaffold({
    super.key,
    required this.body,
    this.title,
    this.subtitle,
    this.leading,
    this.actions,
    this.showLogo = true,
    this.showNotification = true,
    this.bottomNavigationBar,
    this.bottomSheet,
    this.floatingActionButton,
    this.constrainBody = true,
    this.bodyPadding,
    this.useSafeArea = true,
  });

  final Widget body;
  final String? title;
  final String? subtitle;
  final Widget? leading;
  final List<Widget>? actions;
  final bool showLogo;
  final bool showNotification;
  final Widget? bottomNavigationBar;
  final Widget? bottomSheet;
  final Widget? floatingActionButton;
  final bool constrainBody;
  final EdgeInsetsGeometry? bodyPadding;
  final bool useSafeArea;

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    Widget content = body;
    if (constrainBody) {
      content = Align(
        alignment: Alignment.topCenter,
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: AppTheme.maxContentWidth),
          child: Padding(
            padding:
                bodyPadding ??
                EdgeInsets.symmetric(horizontal: horizontalPadding),
            child: content,
          ),
        ),
      );
    }

    if (useSafeArea) {
      content = SafeArea(top: false, child: content);
    }

    return Scaffold(
      backgroundColor: AppTheme.surface,
      appBar: MitologiTopBar(
        title: title,
        subtitle: subtitle,
        leading: leading,
        actions: actions,
        showLogo: showLogo,
        showNotification: showNotification,
      ),
      body: DecoratedBox(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [AppTheme.surface, AppTheme.surfaceContainerLow],
          ),
        ),
        child: content,
      ),
      bottomNavigationBar: bottomNavigationBar,
      bottomSheet: bottomSheet,
      floatingActionButton: floatingActionButton,
    );
  }
}
