import 'package:flutter/material.dart';
import '../../config/theme.dart';

/// Custom painter for T-shirt illustration with measurement indicators
class TShirtPainter extends CustomPainter {
  final Color primaryColor;
  final Color strokeColor;

  TShirtPainter({
    this.primaryColor = AppTheme.primary,
    this.strokeColor = const Color(0xFFCBD5E1),
  });

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = strokeColor
      ..style = PaintingStyle.stroke
      ..strokeWidth = 2;

    final path = Path()
      ..moveTo(size.width * 0.3, size.height * 0.136)
      ..lineTo(size.width * 0.15, size.height * 0.273)
      ..lineTo(size.width * 0.25, size.height * 0.318)
      ..lineTo(size.width * 0.25, size.height * 0.909)
      ..lineTo(size.width * 0.75, size.height * 0.909)
      ..lineTo(size.width * 0.75, size.height * 0.318)
      ..lineTo(size.width * 0.85, size.height * 0.273)
      ..lineTo(size.width * 0.7, size.height * 0.136)
      ..lineTo(size.width * 0.6, size.height * 0.227)
      ..cubicTo(
        size.width * 0.55,
        size.height * 0.273,
        size.width * 0.45,
        size.height * 0.273,
        size.width * 0.4,
        size.height * 0.227,
      )
      ..lineTo(size.width * 0.3, size.height * 0.136);

    // Fill
    final fillPaint = Paint()
      ..color = Colors.white
      ..style = PaintingStyle.fill;
    canvas.drawPath(path, fillPaint);
    canvas.drawPath(path, paint);

    // Measurement lines
    final measurePaint = Paint()
      ..color = primaryColor
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.5;

    // Width line
    canvas.drawLine(
      Offset(size.width * 0.275, size.height * 0.432),
      Offset(size.width * 0.725, size.height * 0.432),
      measurePaint,
    );

    // Length line
    canvas.drawLine(
      Offset(size.width * 0.5, size.height * 0.182),
      Offset(size.width * 0.5, size.height * 0.886),
      measurePaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}

/// Custom painter for Shirt illustration with measurement indicators
class ShirtPainter extends CustomPainter {
  final Color accentColor;
  final Color strokeColor;

  ShirtPainter({
    this.accentColor = AppTheme.accent,
    this.strokeColor = const Color(0xFFCBD5E1),
  });

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = strokeColor
      ..style = PaintingStyle.stroke
      ..strokeWidth = 2;

    final path = Path()
      ..moveTo(size.width * 0.35, size.height * 0.104)
      ..lineTo(size.width * 0.2, size.height * 0.188)
      ..lineTo(size.width * 0.125, size.height * 0.458)
      ..lineTo(size.width * 0.275, size.height * 0.417)
      ..lineTo(size.width * 0.275, size.height * 0.917)
      ..lineTo(size.width * 0.725, size.height * 0.917)
      ..lineTo(size.width * 0.725, size.height * 0.417)
      ..lineTo(size.width * 0.875, size.height * 0.458)
      ..lineTo(size.width * 0.8, size.height * 0.188)
      ..lineTo(size.width * 0.65, size.height * 0.104)
      ..lineTo(size.width * 0.575, size.height * 0.188)
      ..cubicTo(
        size.width * 0.54,
        size.height * 0.229,
        size.width * 0.46,
        size.height * 0.229,
        size.width * 0.425,
        size.height * 0.188,
      )
      ..lineTo(size.width * 0.35, size.height * 0.104);

    // Fill
    final fillPaint = Paint()
      ..color = Colors.white
      ..style = PaintingStyle.fill;
    canvas.drawPath(path, fillPaint);
    canvas.drawPath(path, paint);

    // Placket line
    final placketPaint = Paint()
      ..color = strokeColor
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1;
    canvas.drawLine(
      Offset(size.width * 0.5, size.height * 0.188),
      Offset(size.width * 0.5, size.height * 0.917),
      placketPaint,
    );

    // Buttons
    final buttonPaint = Paint()
      ..color = strokeColor
      ..style = PaintingStyle.fill;

    for (var i = 0; i < 4; i++) {
      canvas.drawCircle(
        Offset(size.width * 0.5, size.height * (0.292 + i * 0.125)),
        3,
        buttonPaint,
      );
    }

    // Measurement lines
    final measurePaint = Paint()
      ..color = accentColor
      ..style = PaintingStyle.stroke
      ..strokeWidth = 1.5;

    // Width line
    canvas.drawLine(
      Offset(size.width * 0.29, size.height * 0.458),
      Offset(size.width * 0.71, size.height * 0.458),
      measurePaint,
    );

    // Height line
    canvas.drawLine(
      Offset(size.width * 0.775, size.height * 0.125),
      Offset(size.width * 0.775, size.height * 0.9),
      measurePaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
