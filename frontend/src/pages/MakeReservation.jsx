import React from "react";
import { Form, DatePicker, Button } from "antd";

export default function MakeReservation() {
  return (
    <Form>
      <Form.Item>
        <DatePicker.RangePicker />
      </Form.Item>
      <Form.Item>
        <Button type="primary" htmlType="submit">
          Make Reservation
        </Button>
      </Form.Item>
    </Form>
  );
}
